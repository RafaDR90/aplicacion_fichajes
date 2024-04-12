<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\HandleInertiaRequests;
use Inertia\Inertia;
use App\Models\User;


class ShareInertiaDataRole
{
    
    public function share(Request $request)
    {
        
        Inertia::share([
            'auth' => function () use ($request) {
                return [
                    'user' => Auth::user() ? [
                        'id' => Auth::id(),
                        'name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                        'role' => Auth::user()->role,
                    ] : null,
                ];
            },
        ]);
    }
}
