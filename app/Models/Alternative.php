<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alternative extends Model
{
    use SoftDeletes;

    protected $table = 'alternatives';
    protected $fillable = [
        'name'
    ];

    public function alternativecriteria()
    {
        return $this->hasMany(AlternativeCriteria::class, 'alternative_id', 'id');
    }
}
