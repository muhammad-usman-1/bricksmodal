<?php

namespace App\Notifications;

use App\Models\CastingApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReleased extends Notification
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
        $cardNumber = optional($this->castingApplication->talent_profile)->getMaskedCardNumber();

        return (new MailMessage)
            ->subject('Payment Released - ' . $projectName)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Great news! Your payment has been released.')
            ->line("**Project:** {$projectName}")
            ->line("**Amount:** $" . number_format($amount, 2))
            ->line("**Card:** {$cardNumber}")
            ->action('View Payment Details', route('talent.payments.index'))
            ->line('The payment should arrive in your account within 2-5 business days.')
            ->line('Please confirm receipt once you receive the payment.');
    }

    public function toArray($notifiable)
    {
        return [
            'casting_application_id' => $this->castingApplication->id,
            'project_name' => optional($this->castingApplication->casting_requirement)->project_name,
            'amount' => $this->castingApplication->getPaymentAmount(),
            'type' => 'payment_released',
        ];
    }
}
