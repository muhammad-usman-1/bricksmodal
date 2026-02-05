<?php

namespace App\Notifications\Channels;

use App\Services\KwtSmsService;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class KwtSmsChannel
{
    /**
     * @var KwtSmsService
     */
    protected $smsService;

    public function __construct(KwtSmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        // KWT SMS service is restricted to OTP ONLY as per user request.
        // Generic notifications via SMS are disabled.
        Log::info('KwtSmsChannel: SMS notifications are disabled. Skipping.', [
            'notification' => get_class($notification),
            'notifiable_id' => $notifiable->id ?? 'unknown',
        ]);
    }
}
