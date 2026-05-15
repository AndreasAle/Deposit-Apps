<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminForumController extends Controller
{
public function index(Request $request)
{
    $q = trim((string) $request->get('q', ''));
    $status = trim((string) $request->get('status', ''));

    $posts = ForumPost::query()
        ->with(['user:id,name', 'media:id,post_id,type,path'])
        ->withCount(['comments', 'media'])
        ->when($q !== '', function ($query) use ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('content', 'like', "%{$q}%")
                  ->orWhereHas('user', function ($u) use ($q) {
                      $u->where('name', 'like', "%{$q}%");
                  });
            });
        })
        ->when(in_array($status, ['pending', 'published', 'rejected'], true), function ($query) use ($status) {
            $query->where('status', $status);
        })
        ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
        ->orderByDesc('id')
        ->paginate(10)
        ->withQueryString();

    return view('admin.forum.index', compact('posts', 'q', 'status'));
}

    public function show(ForumPost $post)
    {
        $post->load([
            'user:id,name',
            'media:id,post_id,type,path,original_name,mime,size,created_at',
            'rootComments.user:id,name',
            'rootComments.children.user:id,name',
            'rootComments.children.children.user:id,name',
        ]);

        return view('admin.forum.show', compact('post'));
    }

    public function destroy(ForumPost $post)
    {
        // Ambil path media dulu sebelum data DB dihapus
        $mediaPaths = $post->media()
            ->whereNotNull('path')
            ->pluck('path')
            ->filter()
            ->values()
            ->all();

        DB::transaction(function () use ($post) {
            /*
             * Hapus komentar dulu.
             * Asumsi relasi comments() berisi semua komentar milik post.
             */
            $post->comments()->delete();

            /*
             * Hapus record media dari database.
             */
            $post->media()->delete();

            /*
             * Hapus post utama.
             */
            $post->delete();
        });

        /*
         * Hapus file fisik setelah transaksi DB sukses.
         * File forum kamu dipanggil pakai asset('storage/'.$m->path),
         * jadi disk yang cocok biasanya public.
         */
        foreach ($mediaPaths as $path) {
            Storage::disk('public')->delete($path);
        }

        return redirect()
            ->route('admin.forum.index')
            ->with('success', 'Post forum berhasil dihapus total beserta komentar dan medianya.');
    }

    public function approve(ForumPost $post)
{
    $post->update([
        'status' => 'published',
    ]);

    return back()->with('success', 'Postingan berhasil di-approve dan sekarang tampil di forum.');
}

public function reject(ForumPost $post)
{
    $post->update([
        'status' => 'rejected',
    ]);

    return back()->with('success', 'Postingan berhasil ditolak dan tidak akan tampil di forum.');
}
}