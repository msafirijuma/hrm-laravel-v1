<?php

namespace App\Notifications;

use App\Models\Payroll;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayrollGeneratedNotification extends Notification
{
    use Queueable;

    public function __construct(public Payroll $payroll)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('This Month Payslip')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("Your Payslip of {$this->payroll->month} generated.")
            ->line('Net Salary: TZS ' . number_format($this->payroll->net_salary, 0))
            ->action('View Payslip', url('/my-payslips/' . $this->payroll->id))
            ->line('Thanks.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'payroll_generated',
            'title'      => 'This Month Payslip',
            'message'    => "Your Payslip of {$this->payroll->month} generated (TZS " . number_format($this->payroll->net_salary, 0) . ").",
            'payroll_id' => $this->payroll->id,
            'url'        => url('/my-payslips/' . $this->payroll->id),
        ];
    }
}