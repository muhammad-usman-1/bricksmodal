<?php

namespace App\Notifications;

use App\Models\CastingApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentRejected extends Notification
{
    use Queueable;

    protected $castingApplication;

    public function __construct(CastingApplication $castingApplication)
    {
        $this->castingApplication = $castingApplication;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $projectName = optional($this->castingApplication->casting_requirement)->project_name ?? 'Project';
        $reason = $this->castingApplication->payment_rejection_reason ?? 'No reason provided';

        return (new MailMessage)
            ->subject('Payment Request Rejected - ' . $projectName)
            ->greeting('Hello,')
            ->line('Your payment request has been rejected.')
            ->line("**Project:** {$projectName}")
            ->line("**Reason:** {$reason}")
            ->action('View Details', route('admin.casting-applications.show', $this->castingApplication))
            ->line('Please contact the super admin if you have questions.');
    }

    public function toArray($notifiable)
    {
        return [
            'casting_application_id' => $this->castingApplication->id,
            'project_name' => optional($this->castingApplication->casting_requirement)->project_name,
            'rejection_reason' => $this->castingApplication->payment_rejection_reason,
            'type' => 'payment_rejected',
        ];
    }
}
