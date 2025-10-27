<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\EventStatusUpdated;
class CvMailerService
{
    // protected string $superAdminEmail = 'superadmin@example.com'; // hardcoded email

    public function sendCv(string $filePath, string $fileName, string $userEmail)
    {
        $emails = [$userEmail];

        foreach ($emails as $email) {
            Mail::send('emails.cv', [], function ($message) use ($email, $filePath, $fileName) {
                $message->to($email)
                    ->subject('CV Submission')
                    ->attach($filePath, [
                        'as' => $fileName,
                        'mime' => mime_content_type($filePath),
                    ]);
            });
        }
    }





    public function SendEventStatus(string $userEmail, string $status)
    {
        Mail::to($userEmail)->send(new EventStatusUpdated($status, $userEmail));
    }
}
