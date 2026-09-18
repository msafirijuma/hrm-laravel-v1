<?php

namespace App\Notifications;

use App\Models\PublicHoliday;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PublicHolidayReminderNotification extends Notification
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
            ->subject('Remainder: Tomorrow is Public Holiday')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("Tomorrow is **{$this->holiday->name}**.")
            ->line('Date: ' . $this->holiday->date->format('d M Y'))
            ->line('Remember: Only assigned staff are required to report to the office tomorrow. To everyone else, have a wonderful day!')
            ->action('View Holidays', route('public-holidays.calendar'))
            ->line('Thanks.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'holiday_reminder',
            'title'   => 'Public Holiday Remainder',
            'message' => "Tomorrow is {$this->holiday->name} ({$this->holiday->date->format('d M Y')}).",
            'url'     => route('dashboard'),
        ];
    }
}