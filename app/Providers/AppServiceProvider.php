<?php

namespace App\Providers;
//
// use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\AdminSetting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
          // URL::forceScheme('https');

          // Share admin settings with all views for background image access
          View::composer('*', function ($view) {
              try {
                  if (Schema::hasTable('admin_settings')) {
                      $settings = AdminSetting::singleton();
                      $view->with('adminSettings', $settings);
                  } else {
                      $view->with('adminSettings', null);
                  }
              } catch (\Exception $e) {
                  // If settings table doesn't exist yet, provide a fallback
                  $view->with('adminSettings', null);
              }
          });
    }
}
