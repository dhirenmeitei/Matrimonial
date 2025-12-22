<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostModel extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $fillable = ['user_id', 'content', 'photo'];

    /**
     * The user who created the post
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Likes for this post
     * Specify foreign key 'post_id' because Laravel would otherwise assume 'post_model_id'
     */
    public function likes()
    {
        return $this->hasMany(PostLikeModel::class, 'post_id');
    }

    /**
     * Comments for this post
     * Specify foreign key 'post_id'
     */
    public function comments()
    {
        return $this->hasMany(PostComment::class, 'post_id');
    }
}
