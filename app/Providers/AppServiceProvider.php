<?php

namespace App\Providers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        // Set Timezone
        // https://gist.github.com/ahmadshobirin/5e761c055b4bae2b13262ce9d58be2cb
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');

        // Define Gate
        Gate::define('admin', function (User $user) {
            return $user->status === 'ADMIN';
        });

        Gate::define('employee', function (User $user) {
            return $user->status === 'EMPLOYEE';
        });

        Gate::define('receptionist', function (User $user) {
            return $user->status === 'RECEPTIONIST';
        });
    }
}
