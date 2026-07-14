<?php

namespace App\Providers;

use App\Models\User;
use App\Notifications\Channels\Msg91Channel;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

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
        // super-admin passes every permission check without explicit grants
        Gate::before(function (User $user, string $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

        Notification::extend('msg91', fn ($app) => $app->make(Msg91Channel::class));
    }
}
