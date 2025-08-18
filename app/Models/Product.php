<?php
namespace App\Models;

use App\Scopes\ProductWithPriceScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'permalink',
        'sku',
        'active',
        'short_description',
        'long_description',
        'product_type',
        'category_id',
        'child_category',
        'grand_child_category',
        'created_by',
        'area_group_id',
        'brand_id',
        'discount_id',
        'shiping_area',
        'featured_image',
        'gallery_media',
        'variant',
    ];

    protected $appends = ['discount_data'];

    protected $with = ['productMeta','brand'];

    public function getMetaAttribute()
    {
        return DB::table('products_meta')
            ->where('product_id', $this->id)
            ->get();
    }

    protected static function booted()
    {
        static::addGlobalScope(new ProductWithPriceScope());
    }


    public function productMeta()
    {
        return $this->hasMany(ProductMeta::class, 'product_id');
    }

    public function virtualProductsWithMeta()
    {
        return $this->hasMany(ProductMeta::class, 'product_id')
            ->where('option', '_virtual')
            ->where('value', 1);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function productMeta1()
    {
        return $this->hasMany(ProductMeta::class)
            ->where('option', '_virtual')
            ->where('value', 1);
    }

    public function collectionProducts()
    {
        return $this->hasMany(CollectionProduct::class, 'product_id');
    }

    public function metas()
    {
        return $this->hasMany(ProductMeta::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id')->where('active', 1);
    }

    public function discountData()
    {
        return $this->belongsTo(Discount::class, 'discount_id')->where('active', 1);
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type', 'id');
    }



    public function getDiscountData()
    {
        $discount = Discount::find($this->discount_id);
        if ($discount) {
            $currentDate = Carbon::now();
            $fromDate = Carbon::parse($discount->from_date);
            $endDate = Carbon::parse($discount->end_date);
            if ($currentDate->greaterThanOrEqualTo($endDate) && $discount->limited == 1) {
                return null;
            }
            if ($currentDate->between($fromDate, $endDate) || $discount->limited == 0) {
                $priceMeta = $this->productMeta->firstWhere('option', '_price');
                $price = $priceMeta ? $priceMeta->value : 0;
                $discountType = $discount->discount_type;
                $discountValue = $discount->discount_value;
                if ($discountType == 1) { // Fixed amount
                    $discountedPrice = max(0, $price - $discountValue);
                } elseif ($discountType == 0) {
                    $discountedPrice = max(0, $price * (1 - $discountValue / 100));
                } else {
                    $discountedPrice = $price;
                }
                return [
                    'product_id' => $this->id,
                    'original_price' => round($price, 2),
                    'discounted_price' => round($discountedPrice, 2),
                    'discount_type' => $discountType,
                    'discount_value' => $discountValue
                ];
            }
        }
        return null;
    }

    public function getCollectionDiscountData()
    {
        $collectionProducts = CollectionProduct::where('product_id', $this->id)->get();
        foreach ($collectionProducts as $collectionProduct) {
            $collection = $collectionProduct->collection;
            $discount = $collection->discount()->first();
            if ($discount && $discount->active == 1) {
                $currentDate = Carbon::now();
                $fromDate = Carbon::parse($discount->from_date);
                $endDate = Carbon::parse($discount->end_date);
                if ($currentDate->greaterThanOrEqualTo($endDate) && $discount->limited == 1) {
                    continue;
                }
                if ($currentDate->between($fromDate, $endDate) || $discount->limited == 0) {
                    $priceMeta = $this->productMeta->firstWhere('option', '_price');
                    $price = $priceMeta ? $priceMeta->value : 0;
                    $discountType = $discount->discount_type;
                    $discountValue = $discount->discount_value;
                    if ($discountType == 1) { // Fixed amount
                        $discountedPrice = max(0, $price - $discountValue);
                    } elseif ($discountType == 0) { // Percentage
                        $discountedPrice = max(0, $price * (1 - $discountValue / 100));
                    } else {
                        $discountedPrice = $price;
                    }
                    return [
                        'product_id' => $this->id,
                        'collection_id' => $collection->id,
                        'original_price' => round($price, 2),
                        'collection_name' => $collection->title,
                        'discounted_price' => round($discountedPrice, 2),
                        'discount_type' => $discountType,
                        'discount_value' => $discountValue
                    ];
                }
            }
        }
        return null;
    }

    public function getDiscountDataAttribute()
    {
        $discountData = $this->getDiscountData();
        $collectionDiscountData = $this->getCollectionDiscountData();

        if ($discountData && $collectionDiscountData) {
            // Combine both data sets, with collection discount data having priority
            $combinedDiscountData = array_merge($discountData, $collectionDiscountData);
            return $combinedDiscountData;
        } elseif ($collectionDiscountData) {
            return $collectionDiscountData;
        } elseif ($discountData) {
            return $discountData;
        }

        return null;
    }




}
