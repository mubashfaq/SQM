<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    /**
     * Send an email.
     *
     * @param array $params Array of parameters including the email subject, receiver email, email blade name, and data to pass to the view
     * @return bool Returns true if the email was sent successfully, otherwise returns false
     */
    public function SendInstantEmail(array $params)
    {
        try {
            \Mail::send('Admin.Emails.'.$params['blade'], $params['data'], function ($message) use ($params) {
                $message->to($params['to']);
                $message->from(env('MAIL_FROM_ADDRESS'));
                $message->subject($params['subject']);
            });

            return true;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
