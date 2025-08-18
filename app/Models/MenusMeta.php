<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenusMeta extends Model
{
    use HasFactory;

    protected $table = 'menus_metas';

    protected $fillable = ['title', 'menu_id', 'url'];

    public function menu()
    {
        return $this->belongsTo(Menus::class, 'menu_id');
    }
}
