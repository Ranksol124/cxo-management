<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Casts\Attribute;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\User;
trait Encryptable
{
    /**
     * Helper to build an Attribute instance for encryptable fields.
     */
    public function makeEncryptableAttribute(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->safeDecrypt($value),
            set: fn($value) => $value ? Crypt::encryptString($value) : null,
        );
    }
    public static function SendMailAll($record)
    {
        $users = User::all();

        foreach ($users as $user) {
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'haseebranksol@gmail.com';
                $mail->Password = 'gipeqmzezdgxexju';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('haseebranksol@gmail.com', 'Your Name');
                $mail->addAddress($user->email, $user->name);

                $mail->isHTML(true);
                $mail->Subject = 'Your Email Subject';
                $mail->Body = '<b>Hello ' . $user->name . '</b>,<br>This is a test email sent via PHPMailer.';
                $mail->AltBody = 'Hello ' . $user->name . ', This is a test email sent via PHPMailer.';

                $mail->send();
            } catch (Exception $e) {

                \Log::error("Message could not be sent to {$user->email}. Error: {$mail->ErrorInfo}");
            }
        }
    }
    private function safeDecrypt($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value; // already plain or not decryptable
        }
    }
}
