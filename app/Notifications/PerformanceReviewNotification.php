<?php

namespace App\Notifications;

use App\Models\PerformanceReview;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PerformanceReviewNotification extends Notification
{
    use Queueable;

    public function __construct(public PerformanceReview $review)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Performance Review')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("Your performance review of {$this->review->period} generated.")
            ->line('Rating: ' . $this->review->rating . '/5')
            ->action('View Review', url('/performance-reviews/' . $this->review->id))
            ->line('Ahsate.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'performance_review',
            'title'   => 'Performance Review',
            'message' => "Your performance review of {$this->review->period} generated (Rating: {$this->review->rating}/5).",
            'url'     => url('/performance-reviews/' . $this->review->id),
        ];
    }
}