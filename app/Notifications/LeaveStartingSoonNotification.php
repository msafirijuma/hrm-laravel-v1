<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveStartingSoonNotification extends Notification
{
    use Queueable;

    public function __construct(public LeaveRequest $leaveRequest)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $emp = $this->leaveRequest->employee;
        return (new MailMessage)
            ->subject('Leave Starting Soon')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("Leave of {$emp->first_name} {$emp->last_name} starting on " . $this->leaveRequest->start_date->format('d M Y') . ".")
            ->line('Days: ' . $this->leaveRequest->days_requested)
            ->action('View', url('/my-leaves'))
            ->line('Thanks.');
    }

    public function toArray(object $notifiable): array
    {
        $emp = $this->leaveRequest->employee;
        return [
            'type'    => 'leave_starting_soon',
            'title'   => 'Leave Starting Soon',
            'message' => "Leave of {$emp->first_name} starting on " . $this->leaveRequest->start_date->format('d M Y'),
            'url'     => url('/my-leaves'),
        ];
    }
}