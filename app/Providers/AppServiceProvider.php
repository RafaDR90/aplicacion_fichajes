<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\User;

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


        Inertia::share([
            'auth' => function () {
                $user = Auth::user();
                $user = User::with(['roles'])->find($user->id);
                dd($user);die;
                return [
                    'user' => $user,

                ];
            },
        ]);
    }
    /* ESTA ES LA ORIGINAL
    public function boot(): void
    {
        
        Inertia::share([
            'auth' => function () {
                return [
                    'user' => Auth::user(),
                ];
            },
        ]);
    }
    */
}
