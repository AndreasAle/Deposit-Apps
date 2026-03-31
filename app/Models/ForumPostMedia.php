<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ForumPostMedia extends Model
{
    use HasFactory;

    protected $table = 'forum_post_media';

    protected $fillable = [
        'post_id',
        'type',
        'path',
        'original_name',
        'mime',
        'size',
    ];

    public function post()
    {
        return $this->belongsTo(ForumPost::class, 'post_id');
    }
}
