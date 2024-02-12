<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
use App\User;

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
        Paginator::useBootstrap();
        Schema::defaultStringLength(191);
        Gate::define('admin', function(User $user){
            return $user->role == 'admin';
            });
        Gate::define('pedagang', function(User $user){
            return $user->role == 'pedagang';
            });
        Gate::define('nelayan', function(User $user){
            return $user->role == 'nelayan';
                });
    }
}
