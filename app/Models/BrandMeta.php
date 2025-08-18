<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class BrandMeta extends Model
{
    use HasFactory;
    protected $table = 'brands_meta';

    protected $fillable = ['brand_id', 'option', 'value'];
    public $timestamps = false;

    public function Brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
