<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'key'     => 'talent_profile_approved',
                'subject' => 'Your BRICKS Model profile has been approved',
                'body'    => "Hi {{name}},\n\nGreat news! Your profile has been approved and is now visible to clients on BRICKS Model.\n\nYou can sign in any time to review projects and manage your profile.\n\nCheers,\n{{app_name}}",
            ],
            [
                'key'     => 'talent_profile_rejected',
                'subject' => 'Your BRICKS Model profile needs attention',
                'body'    => "Hi {{name}},\n\nThank you for submitting your profile to BRICKS Model. We reviewed your details but need a few updates before we can approve it.\n\nNotes from our team:\n{{notes}}\n\nPlease log back in, adjust your information, and resubmit for review.\n\nThanks,\n{{app_name}}",
            ],
            [
                'key'     => 'talent_profile_pending',
                'subject' => 'Your BRICKS Model profile is awaiting review',
                'body'    => "Hi {{name}},\n\nThanks for completing your talent profile. Our team is reviewing your submission now.\n\nWe will email you as soon as a decision has been made.\n\nBest,\n{{app_name}}",
            ],
            [
                'key'     => 'talent_profile_deleted',
                'subject' => 'Your BRICKS Model profile has been removed',
                'body'    => "Hi {{name}},\n\nYour talent profile has been removed from BRICKS Model. If you believe this was a mistake or would like to rejoin, please get in touch with our team.\n\nRegards,\n{{app_name}}",
            ],
            [
                'key'     => 'project_created_notification',
                'subject' => 'New project available: {{project_name}}',
                'body'    => "Hi {{name}},\n\nWe think you might be a great fit for our new project: {{project_name}}.\n\nLocation: {{project_location}}\nDetails: {{project_notes}}\n\nClick below to review the full brief and apply:\n{{project_url}}\n\nGood luck!\n{{app_name}}",
            ],
            [
                'key'     => 'talent_application_selected',
                'subject' => 'You have been selected for {{project_name}}',
                'body'    => "Hi {{name}},\n\nCongratulations! You have been selected for {{project_name}}.\n\nNotes from the project team:\n{{notes}}\n\nWe will be in touch shortly with next steps.\n\nCheers,\n{{app_name}}",
            ],
            [
                'key'     => 'talent_application_rejected',
                'subject' => 'Update on your application for {{project_name}}',
                'body'    => "Hi {{name}},\n\nThank you for applying to {{project_name}}. After review, we will not be moving forward at this time.\n\nNotes from the project team:\n{{notes}}\n\nWe appreciate your interest and encourage you to apply to future opportunities.\n\nBest,\n{{app_name}}",
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(
                ['key' => $template['key']],
                ['subject' => $template['subject'], 'body' => $template['body']]
            );
        }
    }
}
