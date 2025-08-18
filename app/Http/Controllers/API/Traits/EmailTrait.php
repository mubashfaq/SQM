<?php

namespace App\Http\Controllers\API\Traits;

use SendGrid;
use SendGrid\Mail\Mail;
use App\Mail\DynamicMail;
use Illuminate\Http\Request;
use App\Models\GeneralSettings;
use Illuminate\Support\Facades\View;
use App\General\GeneralSettingsClass;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Log; // Import Log facade if you want to log errors


trait EmailTrait
{
 
     public function sendEmail(array $params)
    {
        // Get email settings from database
   $settings = GeneralSettings::whereIn('option', ['_mail_mailer', '_mail_host', '_mail_port', '_mail_username', '_mail_password', '_mail_encryption', '_mail_from_name', '_mail_from_address'])->pluck('value', 'option');
                     
        
        // Validate required settings
        if (empty($settings['_mail_password'])) {
            Log::error("SendGrid API key not found in settings");
            return false;
        }

        if (empty($settings['_mail_from_address'])) {
            Log::error("From email address not found in settings");
            return false;
        }

        // Render the Blade template as HTML
        $htmlContent = View::make($params['template'], $params['data'])->render();
        
        try {
            $email = new Mail();
            
            // Set from address and name from database settings
            $email->setFrom(
                $settings['_mail_from_address'],
                $settings['_mail_from_name'] ?? config('app.name')
            );
            
    
            $email->setSubject($params['subject']);
            $email->addTo($params['to']);
            
            $email->addContent("text/plain", strip_tags($htmlContent));
            $email->addContent("text/html", $htmlContent);

            $sendgrid = new SendGrid($settings['_mail_password']);
            $response = $sendgrid->send($email);
            
            if ($response->statusCode() >= 200 && $response->statusCode() < 300) {
                Log::info("Email sent successfully to: " . $params['to']);
                return true;
            } else {
                Log::error("SendGrid email failed. Status Code: " . $response->statusCode());
                Log::error("Response Body: " . $response->body());
                return false;
            }
            
        } catch (\Exception $e) {
            Log::error("Email sending failed: " . $e->getMessage());
            return false;
        }
    }
}

