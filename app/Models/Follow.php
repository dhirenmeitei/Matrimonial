<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    protected $table = 'follows';

    protected $fillable = [
        'follower_id',
        'following_id',
        'status',
    ];

    public $timestamps = false;

    /**
     * User who SENT the follow request
     */
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    /**
     * User who RECEIVES the follow request
     */
    public function following()
    {
        return $this->belongsTo(User::class, 'following_id');
    }
}
