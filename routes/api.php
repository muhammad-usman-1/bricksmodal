<?php

use App\Http\Controllers\Api\TalentOnboardingApiController;

// Public routes (no authentication required)
Route::prefix('talent')->group(function () {
    Route::post('/otp/send', [TalentOnboardingApiController::class, 'sendOtp'])->name('api.talent.otp.send');
    Route::post('/otp/verify', [TalentOnboardingApiController::class, 'verifyOtp'])->name('api.talent.otp.verify');
});

// Protected routes (require Sanctum authentication)
Route::prefix('talent')->middleware('auth:sanctum')->group(function () {
    Route::post('/onboarding/step-1', [TalentOnboardingApiController::class, 'saveStep1'])->name('api.talent.onboarding.step1');
    Route::post('/onboarding/step-2', [TalentOnboardingApiController::class, 'saveStep2'])->name('api.talent.onboarding.step2');
    Route::post('/onboarding/step-3', [TalentOnboardingApiController::class, 'saveStep3'])->name('api.talent.onboarding.step3');
    Route::post('/onboarding/step-4', [TalentOnboardingApiController::class, 'saveStep4'])->name('api.talent.onboarding.step4');
    Route::post('/onboarding/step-5', [TalentOnboardingApiController::class, 'saveStep5'])->name('api.talent.onboarding.step5');
    Route::get('/onboarding/data', [TalentOnboardingApiController::class, 'getOnboardingData'])->name('api.talent.onboarding.data');
});

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
});
