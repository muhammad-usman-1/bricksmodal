<?php

namespace App\Notifications;

use App\Models\TalentProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TalentSignupCompleted extends Notification implements ShouldQueue
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
            'title' => 'Talent Signup Complete',
            'message' => 'A talent signup complete. Visit profile and approve/reject it.',
            'type' => 'talent_signup',
            'talent_profile_id' => $this->talentProfile->id,
            'time' => now(),
        ];
    }
}
