<?php
namespace App\Http\Controllers\API\Traits;

use App\Models\Product;
use App\Models\GeneralSettings;
use Illuminate\Http\Request;

trait ProductFilterTrait
{
    use JsonResponseTrait;

    public function getFiltered($data, Request $request)
    {
        try {
            if ($data instanceof \Illuminate\Database\Eloquent\Builder) {
                $query = $data;
            } elseif ($data instanceof \Illuminate\Support\Collection) {
                $query = $data->isNotEmpty() ? Product::whereIn('id', $data->pluck('id')->toArray()) : Product::query();
            } else {
                return $this->error('Invalid data provided');
            }
            if (!$query) {
                return $this->error('No data available', 404);
            }

            // Apply search filter
            if ($request->has('search')) {
                $searchTerm = $request->search;
                $query->where('title', 'like', "%{$searchTerm}%");
            }
            $result = $query->first();
            // Apply price range filter
            if ($request->has('min_price') && $request->has('max_price')) {
                $minPrice = $request->min_price;
                $maxPrice = $request->max_price;
                $query->whereHas('productMetaPrice', function ($q) use ($minPrice, $maxPrice) {
                    $q->where('option', '_price')
                        ->whereBetween('value', [$minPrice, $maxPrice]);
                });
            }
            $count = $query->count();
            if ($count > 10) {
                $paginate = $request->has('pagination') ? $request->pagination : 10;
                $result = $query->paginate($paginate);
            } else {
                $result = $query->get();
            }
            return $result;

        } catch (\Exception $e) {
            return $this->error('An error occurred while fetching products');
        }
    }
}
