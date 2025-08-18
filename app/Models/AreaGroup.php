<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaGroup extends Model
{
    protected $fillable = ['title', 'sku', 'country'];
    
    public function groupsMeta()
    {
        return $this->hasMany(GroupsMeta::class,'area_group_id');

    }
}
