<?php

use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\BankDetailController;
use App\Http\Controllers\Admin\CastingApplicationController;
use App\Http\Controllers\Admin\CastingRequirementController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\PaymentDashboardController;
use App\Http\Controllers\Admin\PaymentRequestController;
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

Route::prefix('admin')->as('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminLoginController::class, 'login'])->name('login.submit');
        Route::get('login/google', [AdminLoginController::class, 'redirectToGoogle'])->name('login.google');
        Route::get('login/google/callback', [AdminLoginController::class, 'handleGoogleCallback'])->name('login.google.callback');
        Route::get('unauthorized', [AdminLoginController::class, 'showUnauthorized'])->name('unauthorized');
    });

    Route::post('logout', [AdminLoginController::class, 'logout'])->middleware('auth:admin')->name('logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/', [AdminHomeController::class, 'index'])->name('home');
        Route::get('notifications', [\App\Http\Controllers\Admin\NotificationsController::class, 'index'])->name('notifications.index');
        Route::get('notifications/{notification}', [\App\Http\Controllers\Admin\NotificationsController::class, 'show'])->name('notifications.show');
        Route::get('notifications/mark-all-read', [\App\Http\Controllers\Admin\NotificationsController::class, 'markAllRead'])->name('notifications.mark-all-read');

        // Projects Dashboard (requires project_management permission)
        Route::middleware('admin.module:project_management')->group(function () {
            Route::get('projects', [CastingRequirementController::class, 'index'])->name('projects.dashboard');

            // Casting Requirement
            Route::delete('casting-requirements/destroy', [CastingRequirementController::class, 'massDestroy'])->name('casting-requirements.massDestroy');
            Route::post('casting-requirements/media', [CastingRequirementController::class, 'storeMedia'])->name('casting-requirements.storeMedia');
            Route::post('casting-requirements/ckmedia', [CastingRequirementController::class, 'storeCKEditorImages'])->name('casting-requirements.storeCKEditorImages');
            Route::get('casting-requirements/{casting_requirement}/applicants', [CastingRequirementController::class, 'applicants'])->name('casting-requirements.applicants');
            Route::resource('casting-requirements', CastingRequirementController::class);

            // Casting Application approve/reject (inside project_management)
            Route::post('casting-applications/{casting_application}/approve', [CastingApplicationController::class, 'approve'])->name('casting-applications.approve');
            Route::post('casting-applications/{casting_application}/reject', [CastingApplicationController::class, 'reject'])->name('casting-applications.reject');
        });

        // Talents Dashboard (requires talent_management permission)
        Route::middleware('admin.module:talent_management')->group(function () {
            Route::get('talents', [TalentsDashboardController::class, 'index'])->name('talents.dashboard');

            // Talent Profile
            Route::delete('talent-profiles/destroy', [TalentProfileController::class, 'massDestroy'])->name('talent-profiles.massDestroy');
            Route::post('talent-profiles/{talent_profile}/approve', [TalentProfileController::class, 'approve'])->name('talent-profiles.approve');
            Route::post('talent-profiles/{talent_profile}/reject', [TalentProfileController::class, 'reject'])->name('talent-profiles.reject');
            Route::post('talent-profiles/{talent_profile}/reactivate', [TalentProfileController::class, 'reactivate'])->name('talent-profiles.reactivate');
            Route::resource('talent-profiles', TalentProfileController::class);
        });

        // Payments Dashboard (requires payment_management permission)
        Route::middleware('admin.module:payment_management')->group(function () {
            Route::get('payments', [PaymentDashboardController::class, 'index'])->name('payments.dashboard');
        });

        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::resource('email-templates', \App\Http\Controllers\Admin\EmailTemplateController::class)->only(['index', 'edit', 'update']);

        // Admin Management (Super Admin Only)
        Route::middleware('super.admin')->group(function () {
            Route::resource('admin-management', \App\Http\Controllers\Admin\AdminManagementController::class)->parameters([
                'admin-management' => 'user'
            ]);

            // Role-Permission Management (Super Admin Only)
            Route::get('role-permissions', [\App\Http\Controllers\Admin\RolePermissionController::class, 'index'])->name('role-permissions.index');
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

    // Language
    Route::delete('languages/destroy', [LanguageController::class, 'massDestroy'])->name('languages.massDestroy');
    Route::resource('languages', LanguageController::class);

    // Casting Application
    Route::delete('casting-applications/destroy', [CastingApplicationController::class, 'massDestroy'])->name('casting-applications.massDestroy');
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

        Route::middleware('talent.onboarded')->group(function () {
            Route::get('dashboard', TalentDashboardController::class)->name('dashboard');
            // Additional talent routes will live here.
            Route::get('projects', [\App\Http\Controllers\Talent\ProjectController::class, 'index'])->name('projects.index');
            Route::get('projects/{castingRequirement}', [\App\Http\Controllers\Talent\ProjectController::class, 'show'])->name('projects.show');
            Route::post('projects/{castingRequirement}/apply', [\App\Http\Controllers\Talent\ProjectController::class, 'apply'])->name('projects.apply');

            // Talent Payment Routes
            Route::get('payments', [\App\Http\Controllers\Talent\PaymentController::class, 'index'])->name('payments.index');
            Route::get('payments/card-details', [\App\Http\Controllers\Talent\PaymentController::class, 'cardDetails'])->name('payments.card-details');
            Route::post('payments/card-details', [\App\Http\Controllers\Talent\PaymentController::class, 'storeCardDetails'])->name('payments.store-card-details');
            Route::post('payments/{casting_application}/request', [\App\Http\Controllers\Talent\PaymentController::class, 'requestPayment'])->name('payments.request');
            Route::post('payments/{casting_application}/confirm-received', [\App\Http\Controllers\Talent\PaymentController::class, 'confirmReceived'])->name('payments.confirm-received');
        });
    });
});
