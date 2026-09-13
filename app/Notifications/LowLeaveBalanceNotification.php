<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowLeaveBalanceNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $leaveTypeName,
        public int $remaining
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Low Leave Balance')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("{$this->leaveTypeName}: you are remained with {$this->remaining} days only.")
            ->action('View Leave Balance', url('/'))
            ->line('Thanks.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'low_leave_balance',
            'title'   => 'Low Leave Balance',
            'message' => "{$this->leaveTypeName}: you are remained with {$this->remaining} days only.",
            'url'     => url('/'),
        ];
    }
}