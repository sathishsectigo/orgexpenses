<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('manage-users', function ($user) {
            return $user->hasPermission('manage-users');
        });

        Gate::define('manage-roles', function ($user) {
            return $user->hasPermission('manage-roles');
        });

        Gate::define('manage-permissions', function ($user) {
            return $user->hasPermission('manage-permissions');
        });
    }
}
