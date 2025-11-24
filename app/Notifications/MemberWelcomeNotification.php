<?php

namespace App\Notifications;

use App\Settings\UserEmailSetting;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;
use NotificationChannels\Apn\ApnChannel;
use NotificationChannels\Apn\ApnMessage;

class MemberWelcomeNotification extends Notification
{
    private string $verificationURL;
    private string $loginUrl;
    private string $appName;
    private string $channel;
    private string $password;

    /**
     * Constructor ALWAYS requires password.
     */
    public function __construct(
        string $verificationURL,
        string $password,
        string $channel = 'mail'
    ) {
        $this->verificationURL = $verificationURL;
        $this->password = $password;
        $this->channel = $channel;

        $this->loginUrl = config('app.url') . '/portal';
        $this->appName = config('app.name');
    }

    public function via(object $notifiable): array
    {
        return match ($this->channel) {
            'android' => [FcmChannel::class],
            'ios'     => [ApnChannel::class],
            default   => ['mail'],
        };
    }

    /**
     * EMAIL
     */
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

            ->line("**Email:** {$notifiable->email}")
            ->line("**Password:** {$this->password}")

            ->action(
                $this->replacePlaceholders($settings->button_text, $replacements),
                $this->verificationURL
            )
            ->line('Please update your password after logging in.')
            ->line($this->replacePlaceholders($settings->closing_line, $replacements));
    }

    private function replacePlaceholders(string $text, array $data = []): string
    {
        foreach ($data as $key => $value) {
            $text = str_replace('@' . $key, $value, $text);
        }
        return $text;
    }

    /**
     * ANDROID (FCM)
     */
    public function toFcm(object $notifiable): FcmMessage
    {
        return (new FcmMessage(
            notification: new FcmNotification(
                title: "Welcome to {$this->appName}",
                body: "Your account has been created. Email: {$notifiable->email}"
            )
        ))
            ->data([
                'email' => $notifiable->email,
                'password' => $this->password,
                'loginUrl' => $this->loginUrl,
            ]);
    }

    /**
     * iOS (APNS)
     */
    public function toApn(object $notifiable)
    {
        return ApnMessage::create()
            ->badge(1)
            ->title("Welcome to {$this->appName}")
            ->body("Your account has been created. Email: {$notifiable->email}");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'email' => $notifiable->email,
            'password' => $this->password,
            'channel' => $this->channel,
        ];
    }
}
