<?php

namespace App\Http\Controllers;

use App\Models\ForumPost;
use App\Models\ForumPostMedia;
use App\Models\ForumComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ForumController extends Controller
{
    public function index()
    {
        $posts = ForumPost::query()
            ->with([
                'user',
                'media',
                'comments' => function ($query) {
                    $query
                        ->with('user')
                        ->whereNull('parent_id')
                        ->orderBy('id', 'asc');
                },
            ])
            ->withCount([
                'comments' => function ($query) {
                    $query->whereNull('parent_id');
                },
            ])
            ->where('status', 'published')
            ->orderByDesc('id')
            ->paginate(10);

        return view('team.index', compact('posts'));
    }

    public function storePost(Request $request)
    {
        $request->validate([
            'content' => ['nullable', 'string', 'max:5000'],
            'media.*' => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,webp,pdf'],
        ]);

        $user = auth()->user();

        $hasContent = trim((string) $request->input('content')) !== '';
        $hasFile = $request->hasFile('media');

        if (!$hasContent && !$hasFile) {
            return back()->with('error', 'Isi postingan atau upload file dulu ya bro.');
        }

        DB::beginTransaction();

        try {
            $post = ForumPost::create([
                'user_id' => $user->id,
                'content' => $request->input('content'),
                'status'  => 'pending',
            ]);

            if ($hasFile) {
                foreach ($request->file('media') as $file) {
                    $mime = $file->getMimeType();
                    $type = str_starts_with($mime, 'image/') ? 'image' : 'file';

                    $path = $file->store("forum/{$post->id}", 'public');

                    ForumPostMedia::create([
                        'post_id' => $post->id,
                        'type' => $type,
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'mime' => $mime,
                        'size' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('team.index')
                ->with('success', 'Postingan berhasil dikirim dan menunggu approval admin ✅');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->with('error', 'Gagal posting, coba lagi ya.');
        }
    }

    public function show(ForumPost $post)
    {
        $post->load([
            'user',
            'media',
            'comments' => function ($query) {
                $query
                    ->with('user')
                    ->whereNull('parent_id')
                    ->orderBy('id', 'asc');
            },
        ]);

        $comments = ForumComment::query()
            ->with('user')
            ->where('post_id', $post->id)
            ->whereNull('parent_id')
            ->orderBy('id', 'asc')
            ->paginate(20);

        return view('team.show', compact('post', 'comments'));
    }

    public function storeComment(Request $request, ForumPost $post)
    {
        $request->validate([
            'content' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'integer', 'exists:forum_comments,id'],
        ]);

        if ($post->status !== 'published') {
            return redirect()
                ->route('team.index')
                ->with('error', 'Postingan tidak tersedia.');
        }

        ForumComment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'parent_id' => $request->input('parent_id'),
            'content' => $request->input('content'),
        ]);

        return redirect()
            ->route('team.index')
            ->with('success', 'Komentar terkirim ✅')
            ->with('open_comment_post_id', $post->id);
    }

    public function destroyPost(ForumPost $post)
    {
        $this->authorize('delete', $post);

        DB::beginTransaction();

        try {
            foreach ($post->media as $media) {
                Storage::disk('public')->delete($media->path);
            }

            Storage::disk('public')->deleteDirectory("forum/{$post->id}");

            $post->delete();

            DB::commit();

            return redirect()
                ->route('team.index')
                ->with('success', 'Postingan dihapus ✅');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->with('error', 'Gagal hapus postingan.');
        }
    }

    public function destroyComment(ForumComment $comment)
    {
        $this->authorize('delete', $comment);

        $postId = $comment->post_id;

        try {
            $comment->delete();

            return redirect()
                ->route('team.index')
                ->with('success', 'Komentar dihapus ✅')
                ->with('open_comment_post_id', $postId);
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Gagal hapus komentar.');
        }
    }
}