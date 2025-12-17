<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostModel extends Model
{
    use HasFactory;
    public $table = "posts";
    protected $fillable = ['user_id', 'content', 'photo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(PostLikeModel::class);
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }
}
