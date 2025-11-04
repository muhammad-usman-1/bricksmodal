<?php

use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\BankDetailController;
use App\Http\Controllers\Admin\CastingApplicationController;
use App\Http\Controllers\Admin\CastingRequirementController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\PaymentDashboardController;
use App\Http\Controllers\Admin\PermissionsController;
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
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/talent/login');

// Stripe Webhook
Route::post('webhook/stripe', [App\Http\Controllers\WebhookController::class, 'handleStripeWebhook'])->name('webhook.stripe');

Route::prefix('admin')->as('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminLoginController::class, 'login'])->name('login.submit');
    });

    Route::post('logout', [AdminLoginController::class, 'logout'])->middleware('auth:admin')->name('logout');

    Route::middleware('auth:admin')->group(function () {
    Route::get('/', [AdminHomeController::class, 'index'])->name('home');
    Route::get('notifications', [\App\Http\Controllers\Admin\NotificationsController::class, 'index'])->name('notifications.index');
        Route::get('notifications/{notification}', [\App\Http\Controllers\Admin\NotificationsController::class, 'show'])->name('notifications.show');
        Route::get('notifications/mark-all-read', [\App\Http\Controllers\Admin\NotificationsController::class, 'markAllRead'])->name('notifications.mark-all-read');
            Route::get('projects', [CastingRequirementController::class, 'index'])->name('projects.dashboard');
        Route::get('talents', [TalentsDashboardController::class, 'index'])->name('talents.dashboard');
        Route::get('payments', [PaymentDashboardController::class, 'dashboard'])->name('payments.dashboard');
        Route::post('payments/request', [PaymentDashboardController::class, 'requestPayment'])->name('payments.request');

        // Admin payments management (custom)
        Route::get('payments/manage', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/manage/create/{user}', [\App\Http\Controllers\Admin\PaymentController::class, 'create'])->name('payments.create');
        Route::post('payments/manage/{user}', [\App\Http\Controllers\Admin\PaymentController::class, 'store'])->name('payments.store');

        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::resource('email-templates', \App\Http\Controllers\Admin\EmailTemplateController::class)->only(['index', 'edit', 'update']);

        // Admin Management (Super Admin only)
        Route::middleware('super.admin')->group(function () {
            Route::resource('admins', \App\Http\Controllers\Admin\AdminController::class);
        });

        // Permissions
        Route::delete('permissions/destroy', [PermissionsController::class, 'massDestroy'])->name('permissions.massDestroy');
        Route::resource('permissions', PermissionsController::class);

    // Roles
    Route::delete('roles/destroy', [RolesController::class, 'massDestroy'])->name('roles.massDestroy');
    Route::resource('roles', RolesController::class);

    // Users
    Route::delete('users/destroy', [UsersController::class, 'massDestroy'])->name('users.massDestroy');
    Route::resource('users', UsersController::class);

    // Talent Profile
        Route::delete('talent-profiles/destroy', [TalentProfileController::class, 'massDestroy'])->name('talent-profiles.massDestroy');
    Route::post('talent-profiles/{talent_profile}/approve', [TalentProfileController::class, 'approve'])->name('talent-profiles.approve');
    Route::post('talent-profiles/{talent_profile}/reject', [TalentProfileController::class, 'reject'])->name('talent-profiles.reject');
    Route::post('talent-profiles/{talent_profile}/reactivate', [TalentProfileController::class, 'reactivate'])->name('talent-profiles.reactivate');
    Route::resource('talent-profiles', TalentProfileController::class);

    // Language
    Route::delete('languages/destroy', [LanguageController::class, 'massDestroy'])->name('languages.massDestroy');
    Route::resource('languages', LanguageController::class);

    // Casting Requirement
    Route::delete('casting-requirements/destroy', [CastingRequirementController::class, 'massDestroy'])->name('casting-requirements.massDestroy');
    Route::post('casting-requirements/media', [CastingRequirementController::class, 'storeMedia'])->name('casting-requirements.storeMedia');
    Route::post('casting-requirements/ckmedia', [CastingRequirementController::class, 'storeCKEditorImages'])->name('casting-requirements.storeCKEditorImages');
    Route::get('casting-requirements/{casting_requirement}/applicants', [CastingRequirementController::class, 'applicants'])->name('casting-requirements.applicants');
    Route::resource('casting-requirements', CastingRequirementController::class);

    // Casting Application
    Route::delete('casting-applications/destroy', [CastingApplicationController::class, 'massDestroy'])->name('casting-applications.massDestroy');
    Route::patch('casting-applications/{casting_application}/update-status', [CastingApplicationController::class, 'updateStatus'])->name('casting-applications.update-status');
    Route::post('casting-applications/{casting_application}/approve', [CastingApplicationController::class, 'approve'])->name('casting-applications.approve');
    Route::post('casting-applications/{casting_application}/reject', [CastingApplicationController::class, 'reject'])->name('casting-applications.reject');
    Route::post('casting-applications/{casting_application}/pay', [CastingApplicationController::class, 'pay'])->name('casting-applications.pay');
    Route::get('payments/success', [CastingApplicationController::class, 'paymentSuccess'])->name('payments.success');
    Route::get('casting-applications/{casting_application}/payments/cancel', [CastingApplicationController::class, 'paymentCancel'])->name('payments.cancel');
    Route::resource('casting-applications', CastingApplicationController::class);

        // Bank Detail
        Route::delete('bank-details/destroy', [BankDetailController::class, 'massDestroy'])->name('bank-details.massDestroy');
        Route::resource('bank-details', BankDetailController::class);
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
        Route::get('onboarding/{step}', [OnboardingController::class, 'show'])->name('onboarding.show');
        Route::post('onboarding/{step}', [OnboardingController::class, 'store'])->name('onboarding.store');
        Route::get('pending', [OnboardingController::class, 'pending'])->name('pending');
        Route::middleware(['auth:talent'])->prefix('talent')->name('talent.')->group(function () {
            Route::get('payment-methods', [App\Http\Controllers\Talent\PaymentMethodController::class, 'index'])
                ->name('payment-methods.index');
            Route::get('payment-methods/create', [App\Http\Controllers\Talent\PaymentMethodController::class, 'create'])
                ->name('payment-methods.create');
            Route::post('payment-methods', [App\Http\Controllers\Talent\PaymentMethodController::class, 'store'])
                ->name('payment-methods.store');
        });

        // Admin routes
        Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
            Route::get('payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])
                ->name('payments.index');
            Route::get('payments/create/{user}', [App\Http\Controllers\Admin\PaymentController::class, 'create'])
                ->name('payments.create');
            Route::post('payments/{user}', [App\Http\Controllers\Admin\PaymentController::class, 'store'])
                ->name('payments.store');
        });
        Route::middleware('talent.onboarded')->group(function () {
            Route::get('dashboard', TalentDashboardController::class)->name('dashboard');
            // Additional talent routes will live here.
            Route::get('projects', [\App\Http\Controllers\Talent\ProjectController::class, 'index'])->name('projects.index');
            Route::get('projects/{castingRequirement}', [\App\Http\Controllers\Talent\ProjectController::class, 'show'])->name('projects.show');
            Route::post('projects/{castingRequirement}/apply', [\App\Http\Controllers\Talent\ProjectController::class, 'apply'])->name('projects.apply');
            Route::get('payments', [\App\Http\Controllers\Talent\PaymentController::class, 'index'])->name('payments.index');

            // Talent billing/payment-method routes
            Route::get('payment-methods', [\App\Http\Controllers\Talent\PaymentMethodController::class, 'index'])
                ->name('payment-methods.index');
            Route::get('payment-methods/create', [\App\Http\Controllers\Talent\PaymentMethodController::class, 'create'])
                ->name('payment-methods.create');
            Route::post('payment-methods', [\App\Http\Controllers\Talent\PaymentMethodController::class, 'store'])
                ->name('payment-methods.store');
        });
    });
});
