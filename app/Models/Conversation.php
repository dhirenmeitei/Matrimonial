<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['user_one', 'user_two'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'conversation_users');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
