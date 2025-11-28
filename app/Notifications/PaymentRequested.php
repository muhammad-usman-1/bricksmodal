<?php

namespace App\Notifications;

use App\Models\CastingApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentRequested extends Notification
{
    use Queueable;

    protected $castingApplication;

    public function __construct(CastingApplication $castingApplication)
    {
        $this->castingApplication = $castingApplication;
    }

    public function via($notifiable)
    {
        $channels = ['database'];
        if (config('mail.enabled', true)) {
            $channels[] = 'mail';
        }
        return $channels;
    }

    public function toMail($notifiable)
    {
        $amount = $this->castingApplication->getPaymentAmount();
        $projectName = optional($this->castingApplication->casting_requirement)->project_name ?? 'Project';
        $talentName = optional(optional($this->castingApplication->talent_profile)->user)->name ?? 'Talent';
        $adminName = optional($this->castingApplication->requestedByAdmin)->name ?? 'Admin';

        return (new MailMessage)
            ->subject('New Payment Request - ' . $projectName)
            ->greeting('Hello Super Admin,')
            ->line("A payment request has been submitted by {$adminName}.")
            ->line("**Project:** {$projectName}")
            ->line("**Talent:** {$talentName}")
            ->line("**Amount:** $" . number_format($amount, 2))
            ->action('Review Payment Request', route('admin.payment-requests.index'))
            ->line('Please review and approve this payment request at your earliest convenience.');
    }

    public function toArray($notifiable)
    {
        return [
            'casting_application_id' => $this->castingApplication->id,
            'project_name' => optional($this->castingApplication->casting_requirement)->project_name,
            'amount' => $this->castingApplication->getPaymentAmount(),
            'type' => 'payment_requested',
        ];
    }
}
