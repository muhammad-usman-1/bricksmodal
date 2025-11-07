<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Channels\MailChannel as BaseMailChannel;
use Illuminate\Support\Facades\Log;

class MailChannel extends BaseMailChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        // Check if mail is enabled in .env
        if (!config('mail.enabled', true)) {
            Log::info('Email sending is disabled. Skipping notification.', [
                'notification' => get_class($notification),
                'notifiable' => get_class($notifiable),
                'notifiable_id' => $notifiable->id ?? null,
            ]);
            return;
        }

        // If enabled, send the email normally
        parent::send($notifiable, $notification);
    }
}
