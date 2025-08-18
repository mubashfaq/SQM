<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ProductWithPriceScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereHas('productMeta', function ($query) {
            $query->where('option', '_price')
                ->whereNotNull('value')
                ->where('value', '<>', '');  // Exclude empty values
        });
    }
}
