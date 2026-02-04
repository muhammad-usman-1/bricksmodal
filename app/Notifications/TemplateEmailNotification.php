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

        if (!empty($this->meta['send_sms'])) {
            $channels[] = \App\Notifications\Channels\KwtSmsChannel::class;
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

    public function toKwtSms(object $notifiable): array
    {
        $mobile = $this->meta['mobile_number'] ?? null;
        
        if (!$mobile && $notifiable instanceof \App\Models\User && $notifiable->talentProfile) {
            $mobile = $notifiable->talentProfile->mobile_number;
        }

        return [
            'mobile'  => $mobile,
            'content' => $this->body,
            'lang'    => $this->meta['lang'] ?? 1,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return array_merge($this->meta, [
            'title'   => $this->subject,
            'message' => $this->body,
        ]);
    }
}
