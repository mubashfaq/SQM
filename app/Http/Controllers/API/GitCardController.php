<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\GiftCard;
use Illuminate\Http\Request;

class GitCardController extends Controller{

    public function getGiftCard(GiftCard $giftCard , Request $request)
    {
        return $this->getLatestData($giftCard , $request);
    }
}
