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
        $titleEn = $template->title_en;
        $contentEn = $template->content_en;
        $titleAr = $template->title_ar;
        $contentAr = $template->content_ar;

        // Determine primary language for email/SMS
        if ($language === 'ar' || ($language === 'both' && method_exists($notifiable, 'getPreferredLanguage') && $notifiable->getPreferredLanguage() === 'ar')) {
            $title = $titleAr ?? $titleEn;
            $content = $contentAr ?? $contentEn;
            $langId = 2; // Arabic
        } else {
            $title = $titleEn;
            $content = $contentEn;
            $langId = 1; // English
        }

        // Replace placeholders
        // Replace placeholders in all variants
        foreach ($placeholders as $placeholder => $value) {
            $valStr = (string)$value;
            $title = str_replace('{' . $placeholder . '}', $valStr, $title);
            $content = str_replace('{' . $placeholder . '}', $valStr, $content);
            
            if($titleEn) $titleEn = str_replace('{' . $placeholder . '}', $valStr, $titleEn);
            if($titleAr) $titleAr = str_replace('{' . $placeholder . '}', $valStr, $titleAr);
            
            if($contentEn) $contentEn = str_replace('{' . $placeholder . '}', $valStr, $contentEn);
            if($contentAr) $contentAr = str_replace('{' . $placeholder . '}', $valStr, $contentAr);
        }

        // SMS notifications disabled as per user request. Should only be used for OTP.
        $smsKeys = []; // ['shoot_acceptance', 'shoot_rejection', 'shoot_shortlist', 'talent_signup', 'payment_sent'];
        $shouldSendSms = false; // in_array($key, $smsKeys) || ($meta['send_sms'] ?? false);

        // Prepare meta for SMS and custom payload
        $finalMeta = array_merge([
            'send_sms' => $shouldSendSms,
            'lang'     => $langId,
            'type'     => $key,
            'title_en' => $titleEn,
            'title_ar' => $titleAr,
            'message_en' => $contentEn, // consistent naming
            'message_ar' => $contentAr,
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

        // Email notifications disabled as per user request
        // $notifiable->notify(new TemplateEmailNotification($title, $content, $finalMeta));
    }

    /**
     * Notify all administrators and creative users.
     *
     * @param string $key
     * @param array $placeholders
     * @param array $meta
     * @return void
     */
    public function notifyAdmins(string $key, array $placeholders = [], array $meta = [])
    {
        $admins = \App\Models\User::where(function($q) {
            $q->whereHas('roles', function($qm) {
                $qm->whereIn('title', ['admin', 'superadmin', 'creative']);
            })->orWhere('is_super_admin', 1);
        })->get();

        foreach ($admins as $admin) {
            $this->send($admin, $key, $placeholders, $meta);
        }
    }
}
