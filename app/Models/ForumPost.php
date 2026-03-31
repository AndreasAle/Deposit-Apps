<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ForumPost extends Model
{
    use HasFactory;

    protected $table = 'forum_posts';

    protected $fillable = [
        'user_id',
        'content',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->hasMany(ForumPostMedia::class, 'post_id');
    }

    public function comments()
    {
        return $this->hasMany(ForumComment::class, 'post_id');
    }

    // hanya komentar utama (tanpa parent)
    public function rootComments()
    {
        return $this->hasMany(ForumComment::class, 'post_id')->whereNull('parent_id');
    }
}
