<?php

namespace App\Notifications;

use App\Models\TalentProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TalentProfileSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    private $talentProfile;

    public function __construct(TalentProfile $talentProfile)
    {
        $this->talentProfile = $talentProfile;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New Talent Profile Submitted',
            'message' => "A new talent profile was submitted by {$this->talentProfile->user->name}",
            'type' => 'talent_profile',
            'talent_profile_id' => $this->talentProfile->id,
            'time' => now(),
        ];
    }
}