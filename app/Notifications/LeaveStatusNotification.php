<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveStatusNotification extends Notification
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
        $status = $this->leaveRequest->status;
        $type = $this->leaveRequest->leaveType->name ?? 'Leave';

        $mail = (new MailMessage)
            ->subject($status === 'approved' ? 'Leave Approved' : 'Leave Rejected')
            ->greeting('Hello ' . $notifiable->name . ',');

        if ($status === 'approved') {
            $mail->line("Your leave request of {$type} was approved.")
                 ->line("Date: {$this->leaveRequest->start_date->format('d M Y')} - {$this->leaveRequest->end_date->format('d M Y')}");
        } else {
            $mail->line("Your leave request of {$type} was rejected.")
                 ->line("Reason: " . ($this->leaveRequest->rejection_reason ?? 'No reason provided.'));
        }

        return $mail->action('View My Leave', url('/my-leaves'))
                    ->line('Thanks.');
    }

    public function toArray(object $notifiable): array
    {
        $status = $this->leaveRequest->status;

        return [
            'type'             => 'leave_status',
            'title'            => $status === 'approved' ? 'Leave Approved' : 'Leave Rejected',
            'message'          => $status === 'approved'
                                    ? 'Your leave was approved.'
                                    : 'Your leave was ejected.',
            'leave_request_id' => $this->leaveRequest->id,
            'status'           => $status,
            'url'              => route('my-leaves'),
        ];
    }
}