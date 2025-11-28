<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TemplateEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected string $subject,
        protected string $body,
        protected array $meta = []
    ) {
    }

    public function via(object $notifiable): array
    {
        $channels = ['database'];
        if (config('mail.enabled', true)) {
            $channels[] = 'mail';
        }
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = new MailMessage();
        $message->subject($this->subject);

        foreach (preg_split('~\r?\n\r?\n~', $this->body) as $paragraph) {
            foreach (preg_split('~\r?\n~', trim($paragraph)) as $line) {
                $message->line($line);
            }
            $message->line('');
        }

        return $message;
    }

    public function toArray(object $notifiable): array
    {
        return array_merge($this->meta, [
            'subject' => $this->subject,
            'body'    => $this->body,
        ]);
    }
}
