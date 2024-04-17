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
use Diglactic\Breadcrumbs\Breadcrumbs;


class UserController extends Controller
{

    /**
     * Muestra la página de inicio de sesión.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authenticatedSessionController = new AuthenticatedSessionController();
        return $authenticatedSessionController->create();
    }

    /**
     * Muestra una lista de usuarios.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showUsers(Request $request)
    {
        $eliminados = boolval($request->input('eliminados', false));

        $search = $request->input('search', '');
        $sortField = $request->input('sortField', 'name');

        if ($eliminados) {
            $query = User::onlyTrashed()->with('roles');
        } else {
            $query = User::with('roles');
        }

        if ($search) {
            $query->where(DB::raw('CONCAT(name, " ", apellidos)'), 'like', '%' . $search . '%');
        }
        if (!empty($sortField))
            $query->orderBy($sortField, 'asc');

        $exito = $request->session()->get('exito');
        $request->session()->forget('exito');

        $users = $query->paginate(15);

        $breadcrumbs = Breadcrumbs::generate('showUsers');

        return Inertia::render('Usuario/VistaUsuarios', [
            'users' => $users,
            'search' => $search,
            'sortField' => $sortField,
            'exito' => $exito,
            'eliminados' => $eliminados,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Muestra un usuario específico.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showUser(Request $request, $id = null)
    {
        $id = $request->input('id');
        $user = User::withTrashed()->with(['roles', 'ubicacion', 'horarios'])->find($id);
        $allHorarios = DB::table('horarios')->whereNull('deleted_at')->get();
        $role = 'admin';
        $breadcrumbs = Breadcrumbs::generate('showUser');
        return Inertia::render('Usuario/PerfilUsuario', ['selectedUser' => $user, 'exito' => $request->input('exito'), 'error' => $request->input('error'), 'allHorarios' => $allHorarios, 'role' => $role, 'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Muestra el perfil del usuario actualmente autenticado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function myProfile(Request $request)
    {
        $error = session('error') ?? null;
        $exito = session('exito') ?? null;
        $user = Auth::user();
        $user = User::with(['roles', 'ubicacion', 'horarios'])->find($user->id);
        $allHorarios = DB::table('horarios')->whereNull('deleted_at')->get();
        if ($user->roles->isNotEmpty()) {
            $role = $user->roles[0]->role_name;
        }
        return Inertia::render('Usuario/PerfilUsuario', ['selectedUser' => $user, 'exito' => $exito ?? null, 'error' => $error ?? null,  'allHorarios' => $allHorarios, 'role' => $role ?? null]);
    }

    /**
     * Comprueba si el usuario actualmente autenticado tiene un rol específico.
     *
     * @param  string  $role
     * @return bool
     */
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

    /**
     * Edita el número de teléfono del usuario actualmente autenticado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Edita la dirección del usuario actualmente autenticado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
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

    /**
     * Edita la imagen de perfil del usuario actualmente autenticado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editProfileImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
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

    /**
     * Elimina o restaura un usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteUser(Request $request)
    {
        //si esta eliminado lo restaura sino lo elimina
        $user = User::withTrashed()->find($request->id);
        if ($user->trashed()) {
            $user->restore();
            return redirect()->route('showUsers')->with('exito', 'Usuario restaurado correctamente.');
        } else {
            $user->delete();
            $request->session()->put('exito', 'Usuario eliminado correctamente.');
            return redirect()->route('showUsers')->with('exito', 'Usuario eliminado correctamente.');
        }
    }
}
