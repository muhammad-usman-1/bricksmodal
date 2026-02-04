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
        if (!method_exists($notification, 'toKwtSms')) {
            return;
        }

        $data = $notification->toKwtSms($notifiable);

        if (empty($data['mobile'])) {
            Log::warning('KwtSmsChannel: Missing mobile number for notifiable.', [
                'notifiable_id' => $notifiable->id ?? 'unknown',
            ]);
            return;
        }

        $response = $this->smsService->sendSms(
            $data['mobile'],
            $data['content'],
            $data['lang'] ?? 1
        );

        if (!$response['success']) {
            Log::error('KwtSmsChannel: Failed to send SMS.', [
                'error' => $response['message'],
                'mobile' => $data['mobile'],
            ]);
        }
    }
}
