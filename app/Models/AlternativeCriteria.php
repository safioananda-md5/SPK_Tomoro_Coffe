<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlternativeCriteria extends Model
{
    use SoftDeletes;
    protected $table = 'alternativecriterias';
    protected $fillable = [
        'alternative_id',
        'criteria_id',
        'value',
    ];

    public function alternative()
    {
        return $this->belongsTo(Alternative::class, 'alternative_id', 'id');
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class, 'criteria_id', 'id');
    }
}
