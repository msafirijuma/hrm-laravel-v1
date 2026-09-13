<?php

namespace App\Notifications;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAnnouncementNotification extends Notification
{
    use Queueable;

    public function __construct(public Announcement $announcement)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Announcement: ' . $this->announcement->title)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line($this->announcement->title)
            ->line(str($this->announcement->body)->limit(150))
            ->action('Read an announcement', url('/announcement-board'))
            ->line('Thanks.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'            => 'new_announcement',
            'title'           => 'New Announcement',
            'message'         => $this->announcement->title,
            'announcement_id' => $this->announcement->id,
            'priority'        => $this->announcement->priority,
            'url'             => route('announcements.board'),
        ];
    }
}