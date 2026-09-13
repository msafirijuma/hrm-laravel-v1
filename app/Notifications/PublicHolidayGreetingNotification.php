<?php

namespace App\Notifications;

use App\Models\PublicHoliday;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PublicHolidayGreetingNotification extends Notification
{
    use Queueable;

    public function __construct(public PublicHoliday $holiday)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Happy – ' . $this->holiday->name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("Today is **{$this->holiday->name}**.")
            ->line('We wish you a happy holiday!')
            ->line('Have a good rest.')
            ->line('Thanks.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'holiday_greeting',
            'title'   => 'Happy Holiday!',
            'message' => "Today is {$this->holiday->name}. We wish you a happy holiday!",
            'url'     => route('dashboard'),
        ];
    }
}