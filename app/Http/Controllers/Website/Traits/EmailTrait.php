<?php

namespace App\Http\Controllers\Website\Traits;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Log; // Import Log facade if you want to log errors

trait EmailTrait
{
    // public function SendEmail(array $params)
    // {
    //     $graphApiEndpoint = "https://graph.microsoft.com/v1.0";
    //     $clientId = '5b16e80a-9e3b-457e-917b-bc9b773119cf';
    //     $clientSecret = 'atA8Q~qipzcPx6tgXibz6Jj71Q9CoxAVsWnLRad9';
    //     $tenantId = '54e4c75f-6f16-4a69-9fea-f7ff38d433cf';
    //     $user_id = '43670095-b120-4f18-a376-a1ec25a1ed33';
    
    //     try {
    //         $client = new GuzzleClient();
    //         $tokenEndpoint = "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/token";
    //         $tokenResponse = $client->post($tokenEndpoint, [
    //             'form_params' => [
    //                 'client_id' => $clientId,
    //                 'client_secret' => $clientSecret,
    //                 'scope' => 'https://graph.microsoft.com/.default',
    //                 'grant_type' => 'client_credentials',
    //             ],
    //         ]);
    
    //         $accessToken = json_decode($tokenResponse->getBody()->getContents())->access_token;
    //         $sendEmailEndpoint = "$graphApiEndpoint/users/$user_id/sendMail";

        

    //         $sendEmailPayload = [
    //             'message' => [
    //                 'subject' => $params['subject'],
    //                 'body' => [
    //                     'contentType' => 'HTML',
    //                     'content' => $params['msg'],
    //                 ],
    //                 'toRecipients' => [
    //                     [
    //                         'emailAddress' => ['address' => $params['to']]
    //                     ],
    //                 ],
    //             ],
    //         ];

    //         $response = $client->post($sendEmailEndpoint, [
    //             'headers' => [
    //                 'Authorization' => 'Bearer ' . $accessToken,
    //                 'Accept' => 'application/json',
    //                 'Content-Type' => 'application/json',
    //             ],
    //             'json' => $sendEmailPayload,
    //         ]);
    
    //         return true;
    //     } catch (\Exception $ex) {
    //         Log::error('Error sending email: ' . $ex->getMessage());
    //         return false;
    //     }
    // }
    
}