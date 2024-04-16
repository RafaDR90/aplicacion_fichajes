<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use libphonenumber\PhoneNumberUtil;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'direccion' => 'required|string|max:255|min:8',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $phoneUtil = PhoneNumberUtil::getInstance();
    try {
        $telefono = $phoneUtil->parse($request->telefono, 'ES');
    } catch (\libphonenumber\NumberParseException $e) {
        $validator->errors()->add('telefono', 'El número de teléfono no es válido');
        return redirect()->back()->withErrors($validator)->withInput();
    }

    if (!$phoneUtil->isValidNumber($telefono)) {
        $validator->errors()->add('telefono', 'El número de teléfono no es válido');
        return redirect()->back()->withErrors($validator)->withInput();
    }

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

        $user = User::create([
            'name' => $request->name,
            'apellidos' => $request->apellidos,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        //Auth::login($user);
        //añado 30 dias de vacaciones
        $user->diasVacaciones()->create([
            'dias_disponibles' => 30,
        ]);
        
        $exito = "Empleado '{$user->name} {$user->apellidos}' creado correctamente";

        return redirect(route('showUsers', absolute: false))->with('exito', $exito);

    }
}
