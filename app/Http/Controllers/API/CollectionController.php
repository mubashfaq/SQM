<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;



class CollectionController extends Controller
{
    public function index(Collection $collection ,Request $request)
    {
        return $this->getLatestData($collection , $request);
    }
    public function getCollectionByPermalink(Collection $collection ,$id)
    {
        return  $this->getProductByPermalink($collection,$id);
    }

}
