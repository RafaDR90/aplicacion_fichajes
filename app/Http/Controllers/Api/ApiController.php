<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;



class ApiController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'apellidos' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:4|max:10|confirmed',
        ]);


        //create user
        $user = new User([
            'name' => $request->name,
            'apellidos' => $request->apellidos,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $user->save();
        return response()->json([
            'message' => 'User created successfully'],
            201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);


        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $user = Auth::user();

            //comprueba si el usuario tiene un token
            $user->tokens->each(function ($token, $key) {
                $token->delete();
            });
            

            //crear nuevo token
            $token = $user->createToken('token')->accessToken;
            return response()->json([
                'token' => $token,
                'message' => 'Login successful'],
                200);
        } else {
            return response()->json([
                'message' => 'Invalid email or password'],
                401);
        }

    }

    public function profile()
    {
        $user = Auth::user();
        return response()->json([
            'data' => $user],
            200);
    }

    public function logout(Request $request)
    {
        auth()->user()->token()->revoke();
        return response()->json([
            'message' => 'Logout successful'],
            200);
    }

    public function getResetPasswordToken(Request $request)
    {
        
        $request->validate([
            'email' => 'required|email'
        ]);

        $token = Str::random(60);
        $expiresAt = now()->addMinutes(10);
        
        //Si el usuario ya tiene un token, se elimina
        $checkToken= DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if ($checkToken) {
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();
        }

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
            'expires_at' => now()->addMinutes(10)
        ]);
        

        return response()->json([
            'message' => 'Token created successfully',
            'reset_password_token' => $token],
            200);
    }

    public function changuePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:4|max:30|confirmed',
            'reset_password_token' => 'required|string'
        ]);

        $token = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->reset_password_token)
            ->where('expires_at', '>', now())
            ->first();

        //expira token
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->reset_password_token)
            ->delete();

        if ($token) {
            //comprueba que la contraseña no es la misma que la anterior
            if (Hash::check($request->password, Auth::user()->password)) {
                return response()->json([
                    'message' => 'Debes introducir una contraseña diferente a la anterior'],
                    401);
            }

            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json([
                'message' => 'Password changed successfully'],
                200);
        } else {
            return response()->json([
                'message' => 'Invalid token'],
                401);
        }
    }

    public function fechaYHora(){
        date_default_timezone_set('Europe/Madrid');
        $fecha= date('d-m-Y');
        $hora= date('H:i:s');
        return response()->json([
            'fecha' => $fecha,
            'hora' => $hora],
            200);
    }

    public function emailExists(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = User::where('email', $request->email)->exists();
        return response()->json([
            'email_exists' => $email],
            200);
    }
}
