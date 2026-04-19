<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Periode extends Model
{
    use SoftDeletes;
    protected $table = 'periodes';
    protected $fillable = [
        'name',
        'alternatives',
    ];

    protected $casts = [
        'alternatives' => 'array',
    ];
}
