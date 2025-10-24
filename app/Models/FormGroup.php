<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormGroup extends Model
{
    protected $table = 'form_group';
    protected $fillable = ['group_title', 'form_group_id', 'form_id', 'form_group_serial'];
    public $timestamps = false;
}
