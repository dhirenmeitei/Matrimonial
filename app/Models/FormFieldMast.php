<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormFieldMast extends Model
{
    protected $table = 'form_field_mast';
    protected $fillable = [
        'field_id', 'label_name', 'field_name', 'form_id', 'status',
        'form_group_id', 'field_type', 'readonly', 'field_serial', 'field_class', 'save_to','required','show_hide'
    ];
    public $timestamps = false;
}
