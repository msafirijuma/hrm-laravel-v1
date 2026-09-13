<?php

namespace App\Notifications;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractExpiringNotification extends Notification
{
    use Queueable;

    public function __construct(public Employee $employee, public int $daysLeft)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Contract Is Ending Soon')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("Contract of {$this->employee->first_name} {$this->employee->last_name} ending after {$this->daysLeft}. days")
            ->line('Date: ' . $this->employee->contract_end_date->format('d M Y'))
            ->action('View Employee', url('/employees/' . $this->employee->id))
            ->line('Thanks.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'contract_expiring',
            'title'   => 'Contract Is Ending Soon',
            'message' => "{$this->employee->first_name} {$this->employee->last_name} - {$this->daysLeft} days remain.",
            'url'     => route('employees.show', $this->employee->id),
        ];
    }
}