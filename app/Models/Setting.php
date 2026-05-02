<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = [
        'main_title',
        'main_desc_1',
        'main_desc_2',
        'second_title',
        'second_desc',
    ];
}
