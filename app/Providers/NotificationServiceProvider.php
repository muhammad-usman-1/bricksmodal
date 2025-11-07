<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Override the mail channel to check if email is enabled
        Notification::extend('mail', function ($app) {
            return new \App\Channels\MailChannel(
                $app->make('mail.manager'),
                $app->make(\Illuminate\Mail\Markdown::class)
            );
        });
    }
}
