<?php

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class AdminNotificationSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            [
                'key' => 'admin_talent_profile_submission',
                'name' => 'Admin: Talent Profile Submitted',
                'title_en' => 'New Talent Profile Submitted',
                'title_ar' => 'تم تقديم ملف شخصي جديد للموهبة',
                'content_en' => 'Talent {name} has completed onboarding and submitted their profile for review.',
                'content_ar' => 'أكملت الموهبة {name} عملية التسجيل وقدمت ملفها الشخصي للمراجعة.',
                'language_preference' => 'both',
                'is_active' => true,
            ],
            [
                'key' => 'admin_shoot_application',
                'name' => 'Admin: New Shoot Application',
                'title_en' => 'New Application for {project_name}',
                'title_ar' => 'طلب تقديم جديد لـ {project_name}',
                'content_en' => 'Talent {name} has applied for the {project_name} project.',
                'content_ar' => 'تقدمت الموهبة {name} بطلب لمشروع {project_name}.',
                'language_preference' => 'both',
                'is_active' => true,
            ],
            [
                'key' => 'admin_payment_received',
                'name' => 'Admin: Payment Receipt Confirmed',
                'title_en' => 'Payment Receipt Confirmed',
                'title_ar' => 'تم تأكيد استلام الدفعة',
                'content_en' => 'Talent {name} has confirmed receiving payment for {project_name}.',
                'content_ar' => 'أكدت الموهبة {name} استلام الدفعة لـ {project_name}.',
                'language_preference' => 'both',
                'is_active' => true,
            ],
            [
                'key' => 'admin_profile_status_update',
                'name' => 'Admin: Profile Status Updated',
                'title_en' => 'Profile Status Changed: {status}',
                'title_ar' => 'تغيرت حالة الملف الشخصي: {status}',
                'content_en' => 'Talent {name} profile status has been updated to {status} by {admin_name}.',
                'content_ar' => 'تم تحديث حالة ملف الموهبة {name} إلى {status} بواسطة {admin_name}.',
                'language_preference' => 'both',
                'is_active' => true,
            ],
            [
                'key' => 'admin_application_status_update',
                'name' => 'Admin: Application Status Updated',
                'title_en' => 'Application Status Changed: {status}',
                'title_ar' => 'تغيرت حالة الطلب: {status}',
                'content_en' => 'Application status for {name} on {project_name} has been updated to {status} by {admin_name}.',
                'content_ar' => 'تم تحديث حالة طلب {name} في {project_name} إلى {status} بواسطة {admin_name}.',
                'language_preference' => 'both',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            NotificationTemplate::updateOrCreate(['key' => $template['key']], $template);
        }
    }
}
