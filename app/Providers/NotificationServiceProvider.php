<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('student.sidebar', function ($view) {
            $notifications = auth()->check() ? auth()->user()->notifications()->orderBy('created_at', 'desc')->get() : collect();
            $view->with('notifications', $notifications);
        });
    }

    public function register()
    {
        //
    }
}
