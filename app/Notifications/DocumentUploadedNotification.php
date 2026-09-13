<?php

namespace App\Notifications;

use App\Models\EmployeeDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentUploadedNotification extends Notification
{
    use Queueable;

    public function __construct(public EmployeeDocument $document)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Document Added')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("Document has been added to your profile: {$this->document->title}")
            ->action('View Documents', url('/my-documents'))
            ->line('Thanks.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'document_uploaded',
            'title'   => 'Document Mpya',
            'message' => "Document '{$this->document->title}' added.",
            'url'     => route('my.documents'),
        ];
    }
}