<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'label', 'default', 'placeholder', 'mandatory', 'list'];

    protected $casts = [
        'list' => 'array',
    ];
}
