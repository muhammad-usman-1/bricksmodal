<?php

namespace App\Notifications;

use App\Models\CastingApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CastingApplicationSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    private $application;

    public function __construct(CastingApplication $application)
    {
        $this->application = $application;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New Casting Application',
            'message' => "A new casting application was submitted by {$this->application->talent_profile->user->name}",
            'type' => 'casting_application',
            'application_id' => $this->application->id,
            'time' => now(),
        ];
    }
}