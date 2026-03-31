<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumPost;
use Illuminate\Http\Request;

class AdminForumController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

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
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.forum.index', compact('posts', 'q'));
    }

    public function show(ForumPost $post)
    {
        $post->load([
            'user:id,name',
            'media:id,post_id,type,path,original_name,mime,size,created_at',
            'rootComments.user:id,name',
            'rootComments.children.user:id,name',
            'rootComments.children.children.user:id,name', // cukup 3 level (bisa ditambah kalau perlu)
        ]);

        return view('admin.forum.show', compact('post'));
    }
}
