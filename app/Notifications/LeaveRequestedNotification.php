<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestedNotification extends Notification
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
        $employee = $this->leaveRequest->employee;
        $type = $this->leaveRequest->leaveType->name ?? 'Leave';

        return (new MailMessage)
            ->subject('New Leave Request')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("{$employee->first_name} {$employee->last_name} requested a leave.")
            ->line("Type: {$type}")
            ->line("Start date: {$this->leaveRequest->start_date->format('d M Y')} - {$this->leaveRequest->end_date->format('d M Y')}")
            ->line("Days: {$this->leaveRequest->days_requested}")
            ->action('view request', url('/leave-requests/pending'))
            ->line('Thanks.');
    }

    public function toArray(object $notifiable): array
    {
        $employee = $this->leaveRequest->employee;

        return [
            'type'             => 'leave_requested',
            'title'            => 'New Leave Request',
            'message'          => "{$employee->first_name} {$employee->last_name} requested a leave for a period of ({$this->leaveRequest->days_requested} days).",
            'leave_request_id' => $this->leaveRequest->id,
            'url' => route('leave-requests.pending'),
        ];
    }
}