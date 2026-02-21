<?php

use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\BankDetailController;
use App\Http\Controllers\Admin\CastingApplicationController;
use App\Http\Controllers\Admin\CastingRequirementController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\ImpersonationController;
use App\Http\Controllers\Admin\LabelController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\PaymentDashboardController;
use App\Http\Controllers\Admin\PaymentRequestController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectsDashboardController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TalentProfileController;
use App\Http\Controllers\Admin\TalentsDashboardController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Talent\Auth\LoginController as TalentLoginController;
use App\Http\Controllers\Talent\Auth\RegisterController as TalentRegisterController;
use App\Http\Controllers\Talent\DashboardController as TalentDashboardController;
use App\Http\Controllers\Talent\OnboardingController;
use App\Http\Controllers\Talent\ProfileController as TalentPortalProfileController;
use App\Http\Controllers\Talent\NotificationController as TalentNotificationController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/talent/login');
Route::view('/welcome', 'landing')->name('landing');

// Route::get('/test-s3', function () {
//     \Storage::disk('s3')->put('test/ok.txt', 'It works!');
//     return 'S3 connected';
// });

Route::prefix('admin')->as('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminLoginController::class, 'login'])->name('login.submit');
        Route::get('login/2fa', [AdminLoginController::class, 'show2FAForm'])->name('login.2fa');
        Route::post('login/2fa', [AdminLoginController::class, 'verify2FA'])->name('login.2fa.verify');
        Route::get('login/google', [AdminLoginController::class, 'redirectToGoogle'])->name('login.google');
        Route::get('login/google/callback', [AdminLoginController::class, 'handleGoogleCallback'])->name('login.google.callback');
        Route::get('login/saml', [AdminLoginController::class, 'redirectToSaml'])->name('login.saml');
        Route::get('unauthorized', [AdminLoginController::class, 'showUnauthorized'])->name('unauthorized');
    });

    Route::post('logout', [AdminLoginController::class, 'logout'])->middleware('auth:admin')->name('logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/', [AdminHomeController::class, 'index'])->name('home');
        Route::get('search', [\App\Http\Controllers\Admin\SearchController::class, 'index'])->name('search');
        Route::get('search/check-talent/{id}', [\App\Http\Controllers\Admin\SearchController::class, 'checkTalent'])->name('search.check-talent');
        Route::get('notifications', [\App\Http\Controllers\Admin\NotificationsController::class, 'index'])->name('notifications.index');
        Route::get('notifications/{notification}', [\App\Http\Controllers\Admin\NotificationsController::class, 'show'])->name('notifications.show');
        Route::get('notifications/mark-all-read', [\App\Http\Controllers\Admin\NotificationsController::class, 'markAllRead'])->name('notifications.mark-all-read');
        Route::post('notifications/{notification}/mark-as-read', [\App\Http\Controllers\Admin\NotificationsController::class, 'markAsRead'])->name('notifications.mark-as-read');

        // Projects Dashboard (requires project_management permission)
        Route::middleware('admin.module:project_management')->group(function () {
            Route::get('projects', [CastingRequirementController::class, 'index'])->name('projects.dashboard');
            Route::get('projects/progress', [CastingRequirementController::class, 'progress'])->name('projects.progress');

            // Casting Requirement
            Route::delete('casting-requirements/destroy', [CastingRequirementController::class, 'massDestroy'])->name('casting-requirements.massDestroy');
            Route::post('casting-requirements/media', [CastingRequirementController::class, 'storeMedia'])->name('casting-requirements.storeMedia');
            Route::post('casting-requirements/ckmedia', [CastingRequirementController::class, 'storeCKEditorImages'])->name('casting-requirements.storeCKEditorImages');
            Route::get('casting-requirements/{casting_requirement}/applicants', [CastingRequirementController::class, 'applicants'])->name('casting-requirements.applicants');
            Route::resource('casting-requirements', CastingRequirementController::class);

            // Casting Application approve/reject (inside project_management)
            Route::post('casting-applications/{casting_application}/approve', [CastingApplicationController::class, 'approve'])->name('casting-applications.approve');
            Route::post('casting-applications/{casting_application}/reject', [CastingApplicationController::class, 'reject'])->name('casting-applications.reject');
            Route::post('casting-applications/{casting_application}/shortlist', [CastingApplicationController::class, 'shortlist'])->name('casting-applications.shortlist');
        });

        // Talents Dashboard (requires talent_management permission)
        Route::middleware('admin.module:talent_management')->group(function () {
            Route::get('talents', [TalentsDashboardController::class, 'index'])->name('talents.dashboard');

            // Talent Profile
            Route::get('talent-profiles/suspended', [TalentProfileController::class, 'suspended'])->name('talent-profiles.suspended');
            Route::get('talent-profiles/rejected', [TalentProfileController::class, 'rejected'])->name('talent-profiles.rejected');
            Route::delete('talent-profiles/destroy', [TalentProfileController::class, 'massDestroy'])->name('talent-profiles.massDestroy');
            Route::post('talent-profiles/{talent_profile}/approve', [TalentProfileController::class, 'approve'])->name('talent-profiles.approve');
            Route::post('talent-profiles/{talent_profile}/reject', [TalentProfileController::class, 'reject'])->name('talent-profiles.reject');
            Route::post('talent-profiles/{talent_profile}/reactivate', [TalentProfileController::class, 'reactivate'])->name('talent-profiles.reactivate');
            Route::post('talent-profiles/{talent_profile}/upload-media', [TalentProfileController::class, 'uploadMedia'])->name('talent-profiles.upload-media');
            Route::post('talent-profiles/{talent_profile}/upload-profile-image', [TalentProfileController::class, 'uploadProfileImage'])->name('talent-profiles.upload-profile-image');
            Route::post('talent-profiles/{talent_profile}/presign-profile-image', [TalentProfileController::class, 'presignProfileImage'])->name('talent-profiles.presign-profile-image');
            Route::delete('talent-profiles/{talent_profile}/remove-profile-image', [TalentProfileController::class, 'removeProfileImage'])->name('talent-profiles.remove-profile-image');
            Route::post('talent-profiles/{talent_profile}/suspend', [TalentProfileController::class, 'suspend'])->name('talent-profiles.suspend');
            Route::post('talent-profiles/{talent_profile}/unsuspend', [TalentProfileController::class, 'unsuspend'])->name('talent-profiles.unsuspend');
            Route::post('talent-profiles/combine', [TalentProfileController::class, 'combine'])->name('talent-profiles.combine')->middleware('super.admin');
            Route::resource('talent-profiles', TalentProfileController::class);
        });

        // Payments Dashboard (requires payment_management permission)
        Route::middleware('admin.module:payment_management')->group(function () {
            Route::get('payments', [PaymentDashboardController::class, 'index'])->name('payments.dashboard');
        });

        // Profile
        Route::get('my-profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::put('my-profile', [ProfileController::class, 'update'])->name('profile.update');

        // Two-Factor Authentication
        Route::post('two-factor/enable', [\App\Http\Controllers\Admin\TwoFactorController::class, 'enable'])->name('two-factor.enable');
        Route::post('two-factor/disable', [\App\Http\Controllers\Admin\TwoFactorController::class, 'disable'])->name('two-factor.disable');
        Route::get('two-factor/recovery-codes', [\App\Http\Controllers\Admin\TwoFactorController::class, 'showRecoveryCodes'])->name('two-factor.recovery-codes');
        Route::post('two-factor/recovery-codes', [\App\Http\Controllers\Admin\TwoFactorController::class, 'regenerateRecoveryCodes'])->name('two-factor.regenerate-recovery-codes');

        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::resource('email-templates', \App\Http\Controllers\Admin\EmailTemplateController::class)->only(['index', 'edit', 'update']);
        Route::resource('notification-templates', \App\Http\Controllers\Admin\NotificationTemplateController::class);
        Route::post('notification-templates/{notification_template}/toggle-active', [\App\Http\Controllers\Admin\NotificationTemplateController::class, 'toggleActive'])->name('notification-templates.toggle-active');

        // Outfit Management
        Route::resource('outfits', \App\Http\Controllers\Admin\OutfitController::class);

        // Admin Management (Super Admin Only)
        Route::middleware('super.admin')->group(function () {
            Route::resource('admin-management', \App\Http\Controllers\Admin\AdminManagementController::class)->parameters([
                'admin-management' => 'user'
            ]);

            // Audit Logs (Super Admin Only)
            Route::get('audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-logs.index');
            Route::get('audit-logs/export', [\App\Http\Controllers\Admin\AuditLogController::class, 'export'])->name('audit-logs.export');
            Route::get('audit-logs/{auditLog}', [\App\Http\Controllers\Admin\AuditLogController::class, 'show'])->name('audit-logs.show');

            // Role-Permission Management (Super Admin Only)
            Route::get('role-permissions', [\App\Http\Controllers\Admin\RolePermissionController::class, 'index'])->name('role-permissions.index');
            Route::get('role-permissions/create', [\App\Http\Controllers\Admin\RolePermissionController::class, 'create'])->name('role-permissions.create');
            Route::post('role-permissions', [\App\Http\Controllers\Admin\RolePermissionController::class, 'store'])->name('role-permissions.store');
            Route::get('role-permissions/{role}/edit', [\App\Http\Controllers\Admin\RolePermissionController::class, 'edit'])->name('role-permissions.edit');
            Route::put('role-permissions', [\App\Http\Controllers\Admin\RolePermissionController::class, 'update'])->name('role-permissions.update');

            // Payment Request Management (Super Admin)
            Route::get('payment-requests', [PaymentRequestController::class, 'index'])->name('payment-requests.index');
            Route::get('payment-requests/{casting_application}', [PaymentRequestController::class, 'show'])->name('payment-requests.show');
            Route::post('payment-requests/{casting_application}/approve', [PaymentRequestController::class, 'approve'])->name('payment-requests.approve');
            Route::post('payment-requests/{casting_application}/reject', [PaymentRequestController::class, 'reject'])->name('payment-requests.reject');
            Route::get('payment-requests/{casting_application}/release', [PaymentRequestController::class, 'showReleaseForm'])->name('payment-requests.release-form');
            Route::post('payment-requests/{casting_application}/release', [PaymentRequestController::class, 'release'])->name('payment-requests.release');
        });

        // Casting Application - Request Payment (Regular Admins)
        Route::post('casting-applications/{casting_application}/request-payment', [CastingApplicationController::class, 'requestPayment'])->name('casting-applications.request-payment');
        Route::post('casting-applications/{casting_application}/reject-payment', [CastingApplicationController::class, 'rejectPayment'])->name('casting-applications.reject-payment');
        Route::post('casting-applications/{casting_application}/release-payment', [CastingApplicationController::class, 'releasePayment'])->name('casting-applications.release-payment');

        // Permissions
        Route::delete('permissions/destroy', [PermissionsController::class, 'massDestroy'])->name('permissions.massDestroy');
        Route::resource('permissions', PermissionsController::class);

    // Roles
    Route::delete('roles/destroy', [RolesController::class, 'massDestroy'])->name('roles.massDestroy');
    Route::resource('roles', RolesController::class);

    // Users
    Route::delete('users/destroy', [UsersController::class, 'massDestroy'])->name('users.massDestroy');
    Route::resource('users', UsersController::class);
    Route::post('impersonate/{user}', [ImpersonationController::class, 'start'])->name('impersonate.start');
    Route::match(['post', 'get'], 'impersonate/stop', [ImpersonationController::class, 'stop'])->name('impersonate.stop');

    // Language
    Route::delete('languages/destroy', [LanguageController::class, 'massDestroy'])->name('languages.massDestroy');
    Route::resource('languages', LanguageController::class);

    // Labels
    Route::delete('labels/destroy', [LabelController::class, 'massDestroy'])->name('labels.massDestroy');
    Route::resource('labels', LabelController::class);

    // Casting Application
    Route::delete('casting-applications/destroy', [CastingApplicationController::class, 'massDestroy'])->name('casting-applications.massDestroy');
    Route::resource('casting-applications', CastingApplicationController::class);

        // Bank Detail
        Route::delete('bank-details/destroy', [BankDetailController::class, 'massDestroy'])->name('bank-details.massDestroy');
        Route::resource('bank-details', BankDetailController::class);

        // Onboarding Labels (Arabic Editor)
        Route::get('onboarding-labels', [\App\Http\Controllers\Admin\OnboardingLabelController::class, 'index'])->name('onboarding-labels.index');
        Route::post('onboarding-labels', [\App\Http\Controllers\Admin\OnboardingLabelController::class, 'update'])->name('onboarding-labels.update');

        // Audit Logs (Super Admin only)
        Route::middleware('admin.module:audit_logs')->group(function () {
            Route::get('audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-logs.index');
            Route::get('audit-logs/export', [\App\Http\Controllers\Admin\AuditLogController::class, 'export'])->name('audit-logs.export');
            Route::get('audit-logs/{auditLog}', [\App\Http\Controllers\Admin\AuditLogController::class, 'show'])->name('audit-logs.show');
        });


        // Talent Media
        Route::delete('talent-media/{talentMedia}', [TalentProfileController::class, 'destroyMedia'])->name('talent-media.destroy');
    });
});

Route::prefix('profile')->as('profile.')->middleware(['auth:admin'])->group(function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', [ChangePasswordController::class, 'edit'])->name('password.edit');
        Route::post('password', [ChangePasswordController::class, 'update'])->name('password.update');
        Route::post('profile', [ChangePasswordController::class, 'updateProfile'])->name('password.updateProfile');
        Route::post('profile/destroy', [ChangePasswordController::class, 'destroy'])->name('password.destroyProfile');
    }
});

Route::prefix('talent')->as('talent.')->group(function () {
    Route::middleware('guest:talent')->group(function () {
        Route::get('login', [TalentLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [TalentLoginController::class, 'login'])->name('login.submit');
        Route::get('otp', [TalentLoginController::class, 'showOtpForm'])->name('otp.form');
        Route::post('otp/verify', [TalentLoginController::class, 'verifyOtp'])->name('otp.verify');
        Route::get('register', [TalentRegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('register', [TalentRegisterController::class, 'register'])->name('register.submit');
    });

    Route::post('logout', [TalentLoginController::class, 'logout'])->middleware('auth:talent')->name('logout');

    Route::middleware('auth:talent')->group(function () {
        Route::get('onboarding', [OnboardingController::class, 'start'])->name('onboarding.start');
        Route::get('onboarding/intro', [OnboardingController::class, 'intro'])->name('onboarding.intro');
        Route::get('onboarding/terms', [OnboardingController::class, 'terms'])->name('onboarding.terms');
        Route::post('onboarding/terms/accept', [OnboardingController::class, 'acceptTerms'])->name('onboarding.terms.accept');
        Route::post('onboarding/save-step', [OnboardingController::class, 'saveStep'])->name('onboarding.save-step');
        Route::post('onboarding/presign-additional-photo', [OnboardingController::class, 'presignAdditionalPhoto'])
            ->name('onboarding.presign-additional-photo');
        Route::post('onboarding/presign-id-document', [OnboardingController::class, 'presignIdDocument'])
            ->name('onboarding.presign-id-document');
        Route::get('onboarding/{step}', [OnboardingController::class, 'show'])->name('onboarding.show');
        Route::post('onboarding/{step}', [OnboardingController::class, 'store'])->name('onboarding.store');
        Route::get('pending', [OnboardingController::class, 'pending'])->name('pending');
        Route::get('pending-status', [OnboardingController::class, 'pendingStatus'])->name('pending_status');
        Route::get('rejected', [OnboardingController::class, 'rejected'])->name('rejected');

        Route::middleware('talent.onboarded')->group(function () {
            Route::get('dashboard', TalentDashboardController::class)->name('dashboard');
            // Additional talent routes will live here.
            Route::get('profile', [TalentPortalProfileController::class, 'show'])->name('profile.show');
            Route::put('profile', [TalentPortalProfileController::class, 'update'])->name('profile.update');
            Route::post('profile/upload-image', [TalentPortalProfileController::class, 'uploadImage'])->name('profile.upload-image');
            Route::post('profile/presign-additional-photo', [TalentPortalProfileController::class, 'presignAdditionalPhoto'])->name('profile.presign-additional-photo');
            Route::delete('profile/remove-image', [TalentPortalProfileController::class, 'removeImage'])->name('profile.remove-image');
            // Route::get('projects', [\App\Http\Controllers\Talent\ProjectController::class, 'index'])->name('projects.index');
            // Route::get('projects/{castingRequirement}', [\App\Http\Controllers\Talent\ProjectController::class, 'show'])->name('projects.show');
            // Route::post('projects/{castingRequirement}/apply', [\App\Http\Controllers\Talent\ProjectController::class, 'apply'])->name('projects.apply');

            // Talent Payment Routes (commented out - coming soon)
            // Route::get('payments', [\App\Http\Controllers\Talent\PaymentController::class, 'index'])->name('payments.index');
            // Route::get('payments/card-details', [\App\Http\Controllers\Talent\PaymentController::class, 'cardDetails'])->name('payments.card-details');
            // Route::post('payments/card-details', [\App\Http\Controllers\Talent\PaymentController::class, 'storeCardDetails'])->name('payments.store-card-details');
            // Route::post('payments/{casting_application}/request', [\App\Http\Controllers\Talent\PaymentController::class, 'requestPayment'])->name('payments.request');
            // Route::post('payments/{casting_application}/confirm-received', [\App\Http\Controllers\Talent\PaymentController::class, 'confirmReceived'])->name('payments.confirm-received');

            // Talent Settings Routes
            Route::get('settings', [\App\Http\Controllers\Talent\SettingsController::class, 'index'])->name('settings.index');
            Route::post('settings', [\App\Http\Controllers\Talent\SettingsController::class, 'update'])->name('settings.update');

            // Talent Notifications
            Route::get('notifications', [TalentNotificationController::class, 'index'])->name('notifications.index');
            Route::post('notifications/{id}/mark-as-read', [TalentNotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
            Route::post('notifications/mark-all-read', [TalentNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        });
    });
});

// SAML2 Routes - Manual registration (unconditional to ensure routes are always registered)
Route::middleware([])
    ->prefix('/saml2/')
    ->group(function() {
        Route::prefix('{idpName}')->group(function() {
            $saml2_controller = 'Aacotroneo\Saml2\Http\Controllers\Saml2Controller';
            
            Route::get('/logout', [
                'as' => 'saml2_logout',
                'uses' => $saml2_controller . '@logout',
            ]);
            
            Route::get('/login', [
                'as' => 'saml2_login',
                'uses' => $saml2_controller . '@login',
            ]);
            
            Route::get('/metadata', [
                'as' => 'saml2_metadata',
                'uses' => $saml2_controller . '@metadata',
            ]);
            
            Route::post('/acs', [
                'as' => 'saml2_acs',
                'uses' => $saml2_controller . '@acs',
            ]);
            
            Route::get('/sls', [
                'as' => 'saml2_sls',
                'uses' => $saml2_controller . '@sls',
            ]);
        });
    });
