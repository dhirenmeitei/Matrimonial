<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostLikeModel extends Model
{
    use HasFactory;

    protected $table = 'post_likes';
    protected $fillable = ['post_id', 'user_id'];

    public $timestamps = false;

    /**
     * The post that this like belongs to
     */
    public function post()
    {
        return $this->belongsTo(PostModel::class, 'post_id');
    }

    /**
     * The user who liked the post
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
