<?php

namespace App\Support;

use App\Models\EmailTemplate;
use App\Notifications\TemplateEmailNotification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class EmailTemplateManager
{
    public static function sendToUser($user, string $key, array $variables = [], array $meta = []): void
    {
        if (! $user) {
            return;
        }

        $template = EmailTemplate::findByKey($key);

        $variables = array_merge($variables, [
            'name' => $user->name ?? '',
        ]);

        $subject = $template?->renderSubject($variables) ?? Arr::get($meta, 'fallback_subject', trans('notifications.mail.subject'));
        $body    = $template?->render($variables) ?? Arr::get($meta, 'fallback_body', '');

        try {
            $user->notify(new TemplateEmailNotification($subject, $body, $meta));
        } catch (\Throwable $e) {
            Log::warning('Unable to send template email', [
                'user_id'  => $user->id,
                'key'      => $key,
                'error'    => $e->getMessage(),
            ]);
        }
    }
}
