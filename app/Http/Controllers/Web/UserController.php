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
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('apellidos', 'like', '%' . $search . '%');
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

    public function showUser(Request $request, $id = null, $exito = null, $error = null)
    {
        $id = $request->input('id');
        $user = User::with(['roles', 'ubicacion', 'horarios'])->find($id);
        $allHorarios = DB::table('horarios')->get();
        $role = 'admin';
        return Inertia::render('Usuario/PerfilUsuario', ['selectedUser' => $user, 'exito' => $request->input('exito'), 'error' => $request->input('error'), 'allHorarios' => $allHorarios, 'role' => $role]);
    }

    public function myProfile(Request $request)
    {
        $user = Auth::user();
        $user = User::with(['roles', 'ubicacion', 'horarios'])->find($user->id);
        $allHorarios = DB::table('horarios')->get();
        $role = $user->roles[0]->role_name;
        return Inertia::render('Usuario/PerfilUsuario', ['selectedUser' => $user, 'exito' => $request->input('exito'), 'error' => $request->input('error'),  'allHorarios' => $allHorarios, 'role'=> $role]);
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
}
