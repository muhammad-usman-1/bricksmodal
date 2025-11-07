<?php

namespace App\Notifications;

use App\Models\CastingApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentApproved extends Notification
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
        $amount = $this->castingApplication->getPaymentAmount();
        $projectName = optional($this->castingApplication->casting_requirement)->project_name ?? 'Project';
        $superAdminName = optional($this->castingApplication->approvedBySuperAdmin)->name ?? 'Super Admin';

        return (new MailMessage)
            ->subject('Payment Request Approved - ' . $projectName)
            ->greeting('Hello,')
            ->line("Your payment request has been approved by {$superAdminName}.")
            ->line("**Project:** {$projectName}")
            ->line("**Amount:** $" . number_format($amount, 2))
            ->action('View Details', route('admin.casting-applications.show', $this->castingApplication))
            ->line('The payment will be released to the talent shortly.');
    }

    public function toArray($notifiable)
    {
        return [
            'casting_application_id' => $this->castingApplication->id,
            'project_name' => optional($this->castingApplication->casting_requirement)->project_name,
            'amount' => $this->castingApplication->getPaymentAmount(),
            'type' => 'payment_approved',
        ];
    }
}
