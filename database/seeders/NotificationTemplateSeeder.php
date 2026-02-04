<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationTemplate;

class NotificationTemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            [
                'key' => 'talent_signup',
                'name' => 'Talent Signup',
                'title_en' => 'Welcome to Bricks Modal!',
                'title_ar' => 'مرحباً بك في بريكس مودال!',
                'content_en' => 'Hello, your phone number {phone} has been registered.',
                'content_ar' => 'مرحباً، تم تسجيل رقم هاتفك {phone}.',
                'language_preference' => 'both',
            ],
            [
                'key' => 'talent_profile_submission',
                'name' => 'Talent Profile Submission',
                'title_en' => 'Profile Submitted Successfully',
                'title_ar' => 'تم تقديم الملف الشخصي بنجاح',
                'content_en' => 'Hello {name}, your profile has been successfully submitted and is currently under review by our team. We appreciate your patience while we ensure all details are complete. You should receive a notification once the review process is finished and your profile is approved. Thank you for joining Bricks Modal!',
                'content_ar' => 'مرحباً {name}، لقد تم تقديم ملفك الشخصي بنجاح وهو قيد المراجعة حالياً من قبل فريقنا. نحن نقدر صبرك بينما نتأكد من اكتمال جميع التفاصيل. ستتلقى إشعاراً بمجرد انتهاء عملية المراجعة والموافقة على ملفك الشخصي. شكراً لانضمامك إلى بريكس مودال!',
                'language_preference' => 'both',
            ],
            [
                'key' => 'talent_profile_approval',
                'name' => 'Talent Profile Approved',
                'title_en' => 'Profile Approved',
                'title_ar' => 'تمت الموافقة على الملف الشخصي',
                'content_en' => 'Congratulations {name}, your profile has been approved.',
                'content_ar' => 'تهانينا {name}، تمت الموافقة على ملفك الشخصي.',
                'language_preference' => 'both',
            ],
            [
                'key' => 'talent_profile_rejection',
                'name' => 'Talent Profile Rejected',
                'title_en' => 'Profile Rejected',
                'title_ar' => 'تم رفض الملف الشخصي',
                'content_en' => 'Hi {name}, your profile was rejected. Reason: {notes}',
                'content_ar' => 'مرحباً {name}، تم رفض ملفك الشخصي. السبب: {notes}',
                'language_preference' => 'both',
            ],
            [
                'key' => 'talent_profile_edit',
                'name' => 'Talent Profile Edit/Update',
                'title_en' => 'Profile Updated',
                'title_ar' => 'تم تحديث الملف الشخصي',
                'content_en' => 'Your profile {step} section has been updated.',
                'content_ar' => 'تم تحديث قسم {step} في ملفك الشخصي.',
                'language_preference' => 'both',
            ],
            [
                'key' => 'shoot_creation',
                'name' => 'Shoot Creation',
                'title_en' => 'New Shoot Available',
                'title_ar' => 'يتوفر تصوير جديد',
                'content_en' => 'A new shoot "{project_name}" has been created that matches your profile.',
                'content_ar' => 'تم إنشاء تصوير جديد "{project_name}" يطابق ملفك الشخصي.',
                'language_preference' => 'both',
            ],
            [
                'key' => 'shoot_application',
                'name' => 'Shoot Application',
                'title_en' => 'Application Received',
                'title_ar' => 'تم استلام الطلب',
                'content_en' => 'We received your application for project: {project_name}.',
                'content_ar' => 'لقد استلمنا طلبك للمشروع: {project_name}.',
                'language_preference' => 'both',
            ],
            [
                'key' => 'shoot_shortlist',
                'name' => 'Shoot Shortlist',
                'title_en' => 'Shortlisted!',
                'title_ar' => 'تم اختيارك للمرحلة التالية!',
                'content_en' => 'You have been shortlisted for the project: {project_name}.',
                'content_ar' => 'لقد تم اختيارك للمرحلة التالية للمشروع: {project_name}.',
                'language_preference' => 'both',
            ],
            [
                'key' => 'shoot_acceptance',
                'name' => 'Shoot Acceptance',
                'title_en' => 'Selected for Shoot',
                'title_ar' => 'تم اختيارك للتصوير',
                'content_en' => 'Congratulations! You have been selected for project: {project_name}. Notes: {notes}',
                'content_ar' => 'تهانينا! لقد تم اختيارك للمشروع: {project_name}. ملاحظات: {notes}',
                'language_preference' => 'both',
            ],
            [
                'key' => 'shoot_rejection',
                'name' => 'Shoot Rejection',
                'title_en' => 'Application Status',
                'title_ar' => 'حالة الطلب',
                'content_en' => 'Thank you for applying. Unfortunately, you were not selected for {project_name} this time.',
                'content_ar' => 'شكراً لتقديمك. للأسف، لم يتم اختيارك لـ {project_name} هذه المرة.',
                'language_preference' => 'both',
            ],
            [
                'key' => 'feedback_notification',
                'name' => 'Feedback Notification',
                'title_en' => 'New Feedback Received',
                'title_ar' => 'تم استلام تقييم جديد',
                'content_en' => 'You received a {rating}-star review for project {project_name}. Feedback: {notes}',
                'content_ar' => 'لقد تلقيت تقييماً بـ {rating} نجوم لمشروع {project_name}. الملاحظات: {notes}',
                'language_preference' => 'both',
            ],
            [
                'key' => 'payment_sent',
                'name' => 'Payment Sent',
                'title_en' => 'Payment Released',
                'title_ar' => 'تم إرسال الدفعة',
                'content_en' => 'We have released a payment of {amount} for {project_name}.',
                'content_ar' => 'لقد قمنا بإرسال دفعة بقيمة {amount} لـ {project_name}.',
                'language_preference' => 'both',
            ],
            [
                'key' => 'payment_received',
                'name' => 'Payment Received',
                'title_en' => 'Payment Confirmed',
                'title_ar' => 'تأكيد استلام الدفعة',
                'content_en' => 'Payment receipt for {project_name} has been confirmed.',
                'content_ar' => 'تم تأكيد استلام الدفعة لـ {project_name}.',
                'language_preference' => 'both',
            ],
            [
                'key' => 'payment_request',
                'name' => 'Payment Request',
                'title_en' => 'Payment Requested',
                'title_ar' => 'طلب دفع',
                'content_en' => 'Talent {talent_name} has requested payment for {project_name}.',
                'content_ar' => 'طلب الموهبة {talent_name} دفعاً لـ {project_name}.',
                'language_preference' => 'both',
            ],
            [
                'key' => 'system_activity',
                'name' => 'System Related Activity',
                'title_en' => 'System Update',
                'title_ar' => 'تحديث النظام',
                'content_en' => 'There has been some activity in your account: {detail}',
                'content_ar' => 'كان هناك بعض النشاط في حسابك: {detail}',
                'language_preference' => 'both',
            ],
        ];

        foreach ($templates as $template) {
            NotificationTemplate::updateOrCreate(['key' => $template['key']], $template);
        }
    }
}
