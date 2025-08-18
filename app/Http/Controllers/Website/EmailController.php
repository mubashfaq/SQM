<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function SendInstantEmail(array $params)
    {
        try {
            \Mail::send('Website.Emails.' . $params['blade'], $params['data'], function ($message) use ($params) {
                $message->to($params['to']);
                $message->from(config('mail.from.address'), config('mail.from.name')); // Use the configured values
                $message->subject($params['subject']);
            });

            return true;
        } catch (\Exception $e) {
            return $e;
        }
    }

}
