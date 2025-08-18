<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'permalink',
        'description',
        'active',
        'created_by',
        'discount_id',
    ];

    protected $with = ['products'];
//    protected $appends = ['collection_discount_data'];

    public function getBrandMetaAttribute()
    {
        return DB::table('collections_meta')
            ->where('collection_id', $this->id)
            ->get();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'collection_products');
    }

    public function CollectionMeta()
    {
        return $this->hasMany(CollectionMeta::class); // Adjust the class name as needed
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id')->where('active', 1);
    }

//    public function getCollectionDiscountDataAttribute()
//    {
//        $discount = Discount::find($this->discount_id);
//        if ($discount) {
//            $currentDate = Carbon::now();
//            $fromDate = Carbon::parse($discount->from_date);
//            $endDate = Carbon::parse($discount->end_date);
//            // If the current date is past the end date and the discount is limited, remove the discount
//            if ($currentDate->greaterThanOrEqualTo($endDate) && $discount->limited == 1) {
//                return null;
//            }
//            // If the current date is within the discount period or the discount is not limited
//            if ($currentDate->between($fromDate, $endDate) || $discount->limited == 0) {
//                // Assuming $this refers to the Collection model instance and products are loaded with productMeta
//                $discountedProducts = $this->products->map(function ($product) use ($discount) {
//                    $priceMeta = $product->productMeta->firstWhere('option', '_price');
//                    $price = $priceMeta ? $priceMeta->value : 0;
//                    $discountType = $discount->discount_type;
//                    $discountValue = $discount->discount_value;
//
//                    // Apply the discount based on its type
//                    if ($discountType == 1) { // Fixed amount
//                        $discountedPrice = max(0, $price - $discountValue);
//                    } elseif ($discountType == 0) { // Percentage
//                        $discountedPrice = max(0, $price * (1 - $discountValue / 100));
//                    } else {
//                        $discountedPrice = $price;
//                    }
//
//                    return [
//                        'product_id' => $product->id,
//                        'title' => $product->title,
//                        'original_price' => round($price, 2),
//                        'discounted_price' => round($discountedPrice, 2),
//                        'discount_type' => $discountType,
//                        'discount_value' => $discountValue
//                    ];
//                });
//
//                return $discountedProducts;
//            }
//        }
//    }
}
