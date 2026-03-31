<?php

namespace App\Policies;

use App\Models\ForumComment;
use App\Models\User;

class ForumCommentPolicy
{
    public function delete(User $user, ForumComment $comment): bool
    {
        return $user->id === $comment->user_id || ($user->role ?? null) === 'admin';
    }
}
