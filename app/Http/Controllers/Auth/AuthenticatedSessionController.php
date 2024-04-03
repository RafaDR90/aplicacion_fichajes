<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Alerta;
use App\Services\LocationService;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        if (Auth::check()) {
            return Inertia::render('Fichaje/VistaFichaje');
        }
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        if ($user->requiere_ubicacion) {

            $datosUbicacion = LocationService::getLocation();
            Alerta::create([
                'user_id' => $user->id,
                'tipo' => 'ubicacion',
                'mensaje' => 'Solicitud de ubicación',
                'leido' => 0,
                'datos' => [
                    'countryCode' => $datosUbicacion['countryCode'],
                    'city' => $datosUbicacion['city'],
                    'zip' => $datosUbicacion['zip'],
                    'lat' => $datosUbicacion['lat'],
                    'lon' => $datosUbicacion['lon']
                ],
            ]);

            $user->update([
                'requiere_ubicacion' => 0
            ]);
        }

        return redirect()->intended(route('fichaje', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
