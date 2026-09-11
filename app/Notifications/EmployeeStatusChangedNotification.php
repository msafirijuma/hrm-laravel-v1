<?php

namespace App\Notifications;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeeStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Employee $employee,
        public string $oldStatus,
        public string $newStatus
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Employee Status Changed')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("Status of {$this->employee->first_name} {$this->employee->last_name} changed.")
            ->line("From: {$this->oldStatus} → To: {$this->newStatus}")
            ->action('View Employee', url('/employees/' . $this->employee->id))
            ->line('Thanks.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'status_changed',
            'title'   => 'Status Changed',
            'message' => "{$this->employee->first_name} {$this->employee->last_name}: {$this->oldStatus} → {$this->newStatus}",
            'url'     => url('/employees/' . $this->employee->id),
        ];
    }
}