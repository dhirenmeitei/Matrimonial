<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormMast extends Model
{
    use HasFactory;
    protected $table = 'form_mast';
    protected $fillable = ['form_title', 'status', 'form_id'];
    public $timestamps = false;

    public function fields()
    {
        return $this->hasMany(FormFieldMast::class, 'form_id', 'form_id')->orderBy('field_serial');
    }

    public function groups()
    {
        return $this->hasMany(FormGroup::class, 'form_id', 'form_id')->orderBy('form_group_serial');
    }
}

