<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\Traits\OrderProcessTrait;
use App\Http\Controllers\API\Traits\ProductFilterTrait;
use App\Http\Controllers\API\Traits\JsonResponseTrait;
use App\Http\Controllers\API\Traits\ProductTrait;
use App\Http\Controllers\API\Traits\UserTrait;
use App\Http\Controllers\API\Traits\validationTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests,
        JsonResponseTrait,
        ProductFilterTrait,
        ProductTrait,
        UserTrait,
        OrderProcessTrait,
        ValidationTrait;
}


