<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Resources\UserResource;
use Symfony\Component\VarDumper\VarDumper;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    public function index()
    {
        $authenticatedSessionController = new AuthenticatedSessionController();
        return $authenticatedSessionController->create();
    }

    public function showUsers(Request $request)
    {

        $search = $request->input('search', '');
        $sortField = $request->input('sortField', 'name');

        $query = User::with('roles');

        if ($search) {
            $query->where(DB::raw('CONCAT(name, " ", apellidos)'), 'like', '%' . $search . '%');
        }
        if (!empty($sortField))
            $query->orderBy($sortField, 'asc');

        $exito = $request->session()->get('exito');
        $request->session()->forget('exito');

        $users = $query->paginate(15);

        return Inertia::render('Usuario/VistaUsuarios', [
            'users' => $users,
            'search' => $search,
            'sortField' => $sortField,
            'exito' => $exito
        ]);
    }

    public function showUser(Request $request, $id = null)
    {
        $id = $request->input('id');
        $user = User::with(['roles', 'ubicacion', 'horarios'])->find($id);
        $allHorarios = DB::table('horarios')->get();
        $role = 'admin';
        return Inertia::render('Usuario/PerfilUsuario', ['selectedUser' => $user, 'exito' => $request->input('exito'), 'error' => $request->input('error'), 'allHorarios' => $allHorarios, 'role' => $role]);
    }

    public function myProfile(Request $request)
    {
        $error = session('error') ?? null;
        $exito = session('exito') ?? null;
        $user = Auth::user();
        $user = User::with(['roles', 'ubicacion', 'horarios'])->find($user->id);
        $allHorarios = DB::table('horarios')->get();
        if ($user->roles->isNotEmpty()) {
            $role = $user->roles[0]->role_name;
        }
        return Inertia::render('Usuario/PerfilUsuario', ['selectedUser' => $user, 'exito' => $exito ?? null, 'error' => $error ?? null,  'allHorarios' => $allHorarios, 'role' => $role ?? null]);
    }

    public static function hasRole($role)
    {
        $user = Auth::user();
        $roles = $user->roles;
        foreach ($roles as $rol) {
            if ($rol->role_name == $role) {
                return true;
            }
        }
        return false;
    }

    public function editPhone(Request $request)
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $numberProto = $phoneUtil->parse($request->editedPhone, 'ES');
            if (!$phoneUtil->isValidNumber($numberProto)) {
                //redirecciono a la ruta anterior con un mensaje de error
                return redirect()->route('myProfile')->with('error', 'El número de teléfono no es válido.');
            }
        } catch (\libphonenumber\NumberParseException $e) {
            return redirect()->route('myProfile')->with('error', 'El número de teléfono no es válido.');
        }

        //valido que haya cambiado el telefono
        if ($request->editedPhone == Auth::user()->telefono) {
            return redirect()->route('myProfile')->with('error', 'El número de teléfono no ha cambiado.');
        }
        
        $user = User::find(Auth::user()->id);
        $user->telefono = $request->editedPhone;
        $user->save();
        return redirect()->route('myProfile')->with('exito', 'Número de teléfono actualizado correctamente.');
    }

    public function editAddress(Request $request)
    {
        $validator= Validator::make($request->all(), [
            'editedAddress' => 'required|string|max:255|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->route('myProfile')->with('error', $validator->errors()->first());
        }

        //valido que haya cambiado la direccion
        if ($request->editedAddress == Auth::user()->direccion) {
            return redirect()->route('myProfile')->with('error', 'La dirección no ha cambiado.');
        }

        $user = User::find(Auth::user()->id);
        $user->direccion = $request->editedAddress;
        $user->save();
        return redirect()->route('myProfile')->with('exito', 'Dirección actualizada correctamente.');
    }

    public function editProfileImage(Request $request)
    {
        $validator= Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            //devuelvo el error correspondiente
            return back()->with('error', $validator->errors()->first());
        }
        //el nombre de la foto es el email
        $imageName = Auth::user()->email . '.' . $request->file->extension();
        $request->file->move(public_path('images'), $imageName);
        $user = User::find(Auth::user()->id);
        $user->image_url = $imageName;
        $user->save();
        return back()->with('exito', 'Imagen de perfil actualizada correctamente.');

    }
}
