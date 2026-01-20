<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Criteria extends Model
{
    use SoftDeletes;
    protected $table = 'criterias';
    protected $fillable = [
        'name',
        'type',
        'weight',
        'description',
    ];
}
