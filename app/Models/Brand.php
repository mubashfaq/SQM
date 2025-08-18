<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'permalink',
        'logo_image',
        'cover_image',
        'created_by',
        'active',
    ];
    public function getBrandMetaAttribute()
    {
        return DB::table('brands_meta')
            ->where('brand_id', $this->id)
            ->get();
    }
    public function brandMeta()
    {
        return $this->hasMany(BrandMeta::class); // Adjust the class name as needed
    }
    public function products(){
        return $this->hasMany(Product::class);
    }
    public function activeProducts(){
        return $this->hasMany(Product::class)->where('active',1)->whereNotNull('featured_image');
    }
    public function productsBySKU($sku)
    {
        $brand = $this->where('permalink', $sku)->first();
        if($brand){
            return $brand->activeProducts;
        }
    }
}
