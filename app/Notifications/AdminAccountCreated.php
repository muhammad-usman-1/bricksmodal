<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminAccountCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public $password;
    public $permissions;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $password, array $permissions = [])
    {
        $this->password = $password;
        $this->permissions = $permissions;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $permissionsList = [];
        if (!empty($this->permissions['project_management'])) {
            $permissionsList[] = 'Project Management';
        }
        if (!empty($this->permissions['talent_management'])) {
            $permissionsList[] = 'Talent Management';
        }
        if (!empty($this->permissions['payment_management'])) {
            $permissionsList[] = 'Payment Management';
        }

        $permissionsText = !empty($permissionsList)
            ? implode(', ', $permissionsList)
            : 'No module permissions assigned';

        $canMakePayments = !empty($this->permissions['can_make_payments'])
            ? 'Yes - You can process payments directly'
            : 'No - You must request approval from Super Admin';

        return (new MailMessage)
                    ->subject('Your Admin Account Has Been Created')
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('A new admin account has been created for you on the BRICKS Model Admin Panel.')
                    ->line('**Login Credentials:**')
                    ->line('Email: **' . $notifiable->email . '**')
                    ->line('Password: **' . $this->password . '**')
                    ->line('')
                    ->line('**Your Permissions:**')
                    ->line('Modules: ' . $permissionsText)
                    ->line('Payment Authorization: ' . $canMakePayments)
                    ->line('')
                    ->line('Please keep your credentials secure and change your password after your first login.')
                    ->action('Login to Admin Panel', url('/admin/login'))
                    ->line('If you did not expect this account, please contact the system administrator.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'password' => $this->password,
            'permissions' => $this->permissions,
        ];
    }
}
