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
use Carbon\Carbon;

class PlanExpiryNotification extends Notification
{
    // use Queueable;

    private string $password;
    private string $loginUrl;
    private string $appName;
    private string $channel; // mail, android, ios
    public $planName;
    public $planExpiry;
    /**
     * Create a new notification instance.
     */
    public function __construct($planName, $planExpiry,$channel = 'mail')
    {
        
        $this->loginUrl  = config('app.url') . '/portal';
        $this->appName   = config('app.name');
        $this->planName = $planName;
        $this->planExpiry = Carbon::parse($planExpiry); 
        $this->channel = $channel;
         $this->password   = ''; // or pass real password if needed
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

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Reminder: Your {$this->planName} Plan Expires in 7 Days")
            ->greeting("Hi " . $notifiable->name . ",")
            ->line("We wanted to let you know that your **{$this->planName}** plan will expire on " . $this->planExpiry->format('F j, Y') . "**.")
            ->line("To make sure you don’t lose access to your benefits, please renew your plan before the expiry date.")
            //->action('Renew Plan Now', url('/plans/renew'))
            ->line("Thank you for choosing {$this->appName}! We’re excited to keep serving you.");
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
