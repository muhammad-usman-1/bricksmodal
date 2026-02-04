<?php

namespace App\Services;

use App\Models\NotificationTemplate;
use App\Notifications\TemplateEmailNotification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send a notification based on a template key.
     *
     * @param mixed $notifiable The user or model to be notified.
     * @param string $key The unique key of the notification template.
     * @param array $placeholders Associative array of placeholders and their values.
     * @param array $meta Additional metadata for the notification.
     * @return void
     */
    public function send($notifiable, string $key, array $placeholders = [], array $meta = [])
    {
        $template = NotificationTemplate::where('key', $key)->where('is_active', true)->first();

        if (!$template) {
            Log::warning("Notification template not found or inactive: {$key}");
            return;
        }

        $language = $template->language_preference;
        $title = $template->title_en;
        $content = $template->content_en;
        $langId = 1; // English for KWT SMS

        if ($language === 'ar' || ($language === 'both' && method_exists($notifiable, 'getPreferredLanguage') && $notifiable->getPreferredLanguage() === 'ar')) {
            $title = $template->title_ar ?? $title;
            $content = $template->content_ar ?? $content;
            $langId = 2; // Arabic for KWT SMS
        } else {
            $langId = 1; // English for KWT SMS
        }

        // Replace placeholders
        foreach ($placeholders as $placeholder => $value) {
            $title = str_replace('{' . $placeholder . '}', (string)$value, $title);
            $content = str_replace('{' . $placeholder . '}', (string)$value, $content);
        }

        // Prepare meta for SMS and custom payload
        $finalMeta = array_merge([
            'send_sms' => true,
            'lang'     => $langId,
            'type'     => $key,
        ], $meta);

        // If mobile number isn't provided in meta, try to find it
        if (empty($finalMeta['mobile_number'])) {
            if ($notifiable instanceof \App\Models\User && $notifiable->talentProfile) {
                // Get mobile number from talent profile table
                $profile = $notifiable->talentProfile;
                $mobile = $profile->mobile_number;
                
                if ($mobile) {
                    $finalMeta['mobile_number'] = $mobile;
                }
            }
        }

        $notifiable->notify(new TemplateEmailNotification($title, $content, $finalMeta));
    }
}
