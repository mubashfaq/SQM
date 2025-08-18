<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupsMeta extends Model
{
    protected $fillable = ['title', 'postcode'];

    public function areaGroup()
    {
        return $this->belongsTo(AreaGroup::class);
    }
}
