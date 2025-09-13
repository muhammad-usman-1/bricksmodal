<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Talent Profile
    Route::delete('talent-profiles/destroy', 'TalentProfileController@massDestroy')->name('talent-profiles.massDestroy');
    Route::resource('talent-profiles', 'TalentProfileController');

    // Language
    Route::delete('languages/destroy', 'LanguageController@massDestroy')->name('languages.massDestroy');
    Route::resource('languages', 'LanguageController');

    // Casting Requirement
    Route::delete('casting-requirements/destroy', 'CastingRequirementController@massDestroy')->name('casting-requirements.massDestroy');
    Route::post('casting-requirements/media', 'CastingRequirementController@storeMedia')->name('casting-requirements.storeMedia');
    Route::post('casting-requirements/ckmedia', 'CastingRequirementController@storeCKEditorImages')->name('casting-requirements.storeCKEditorImages');
    Route::resource('casting-requirements', 'CastingRequirementController');

    // Casting Application
    Route::delete('casting-applications/destroy', 'CastingApplicationController@massDestroy')->name('casting-applications.massDestroy');
    Route::resource('casting-applications', 'CastingApplicationController');

    // Bank Detail
    Route::delete('bank-details/destroy', 'BankDetailController@massDestroy')->name('bank-details.massDestroy');
    Route::resource('bank-details', 'BankDetailController');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
