<?php

namespace App\Notifications;
use Illuminate\Support\HtmlString;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $title;
    public string $description;
    public string $date;
    public string $type;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $title, string $description, string $date, string $type)
    {
        $this->title = $title;
        $this->description = $description;
        $this->date = $date;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->title)
            ->line(new HtmlString('Description' . $this->description))
            ->line(new HtmlString('Type: ' . $this->type))
            ->line('Date: ' . $this->date)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'date' => $this->date,
            'type' => $this->type,
        ];
    }
}
