<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\URL;

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
        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Inertia::share([
            'auth' => function () {
                $user = Auth::user();
                $user = User::with(['roles'])->find($user->id);
                return [
                    'user' => $user,

                ];
            },
        ]);
    }
}
