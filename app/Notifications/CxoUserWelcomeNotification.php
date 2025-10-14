<?php

namespace App\Notifications;

use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;
use NotificationChannels\Apn\ApnChannel;
use NotificationChannels\Apn\ApnMessage;
use App\Settings\UserEmailSetting;

class CxoUserWelcomeNotification extends Notification
{
    // use Queueable;

    private string $password;
    private string $loginUrl;
    private string $appName;
    private string $channel; // mail, android, ios

    /**
     * Create a new notification instance.
     */
    public function __construct(string $password, string $channel = 'mail')
    {
        $this->password  = $password;
        $this->loginUrl  = config('app.url') . '/portal';
        $this->appName   = config('app.name');
        $this->channel   = $channel; 
    }

    /**
     * Delivery channels dynamically.
     */
    public function via(object $notifiable): array
    {
        return match ($this->channel) {
            'android' => [FcmChannel::class],
            'ios'     => [ApnChannel::class],
            default   => ['mail'],
        };
    }

    public function toMail(object $notifiable): MailMessage
    {
        $settings = app(UserEmailSetting::class);
        $replacements = [
            'subject' => $this->appName,
            'name' => $notifiable->name,
            'email' => $notifiable->email,
            'password' => $this->password,
            'appName' => config('app.name'),
            'appUrl' => config('app.url'),
        ];
        return (new MailMessage)
            ->subject($this->replacePlaceholders($settings->subject, $replacements))
            ->greeting($this->replacePlaceholders($settings->greeting, $replacements))
            ->line($this->replacePlaceholders($settings->intro_line, $replacements))
            ->line($this->replacePlaceholders($settings->credentials_line, $replacements))
            ->line($this->replacePlaceholders($settings->password_line, $replacements))
            ->action(
                $this->replacePlaceholders($settings->button_text, $replacements),
                $this->loginUrl
            )
            ->line($this->replacePlaceholders($settings->closing_line, $replacements));
    }

    //placeholder replacer
    function replacePlaceholders(string $text, array $data = []): string
    {
        foreach ($data as $key => $value) {
            $text = str_replace('@' . $key, $value, $text);
        }
        return $text;
    }

    /**
     * FCM Notification (Android).
     */
    public function toFcm(object $notifiable): FcmMessage
    {
        return (new FcmMessage(notification: new FcmNotification(
            title: 'Welcome to ' . $this->appName,
            body: 'Your account has been created. Email: ' . $notifiable->email,
        )))
            ->data([
                'email'    => $notifiable->email,
                'password' => $this->password,
                'loginUrl' => $this->loginUrl,
            ])
            ->custom([
                'android' => [
                    'notification' => [
                        'color' => '#0A0A0A',
                    ],
                ],
            ]);
    }

    /**
     * APNs Notification (iOS).
     */
    public function toApn(object $notifiable)
    {
        return ApnMessage::create()
            ->badge(1)
            ->title('Welcome to ' . $this->appName)
            ->body("Your account has been created. Email: {$notifiable->email}");
    }

    /**
     * Optional DB log.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'email'    => $notifiable->email,
            'password' => $this->password,
            'channel'  => $this->channel,
        ];
    }
}
