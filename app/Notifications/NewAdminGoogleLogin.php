<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAdminGoogleLogin extends Notification implements ShouldQueue
{
    use Queueable;

    public $newUser;

    /**
     * Create a new notification instance.
     */
    public function __construct($newUser)
    {
        $this->newUser = $newUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = [];
        if (config('mail.enabled', true)) {
            $channels[] = 'mail';
        }
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Admin Logged in via Google')
                    ->greeting('Hello Super Admin!')
                    ->line('A new admin has logged in using Google OAuth.')
                    ->line('Name: ' . $this->newUser->name)
                    ->line('Email: ' . $this->newUser->email)
                    ->line('Please review the new admin account.')
                    ->action('View Admin Management', url('/admin/admin-management'))
                    ->line('If this is unexpected, please take appropriate action.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'new_user' => $this->newUser,
        ];
    }
}
