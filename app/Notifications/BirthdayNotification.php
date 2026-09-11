<?php

namespace App\Notifications;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BirthdayNotification extends Notification
{
    use Queueable;

    public function __construct(public Employee $employee)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Birthday Today')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("Today is the birthday of {$this->employee->first_name} {$this->employee->last_name}.")
            ->line('Send congrats!')
            ->line('Thanks.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'birthday',
            'title'   => 'Birthday Today',
            'message' => "Today is the birthday of {$this->employee->first_name} {$this->employee->last_name}.",
            'url'     => url('/employees/' . $this->employee->id),
        ];
    }
}