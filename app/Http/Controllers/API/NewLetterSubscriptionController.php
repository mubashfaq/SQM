<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\Traits\ValidationTrait;
use App\Http\Controllers\Controller;
use App\Models\Contactu;
use App\Models\GiftCard;
use App\Models\NewsLetterSubscription;
use Illuminate\Http\Request;

class NewLetterSubscriptionController extends Controller{

    use ValidationTrait;
    public function store(NewsLetterSubscription $newsLetterSubscription,Request $request)
    {
        return $this->newsLetter($newsLetterSubscription,$request);
    }

    public function contactUs(Request $request){
        $validator = $this->contactUsValidationRequest($request);
        if($validator->fails()){
            return $this->handleValidationFailure($validator);
        }
        $contact = Contactu::create($request->all());
        if (!$contact){
            return $this->message('something went wrong');
        }
        return $this->message('Message sent successfully');
    }

}
