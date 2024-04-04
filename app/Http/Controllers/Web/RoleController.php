<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;



class RoleController extends Controller
{
    public function cambiarRol(Request $request)
    {
        $error = null;
        $request->validate([
            'id' => 'required|integer',
            //el rol tiene que existir o ser 'Normal'
            'rol' => 'required|string|in:normal,admin,super-admin'
        ]);

        $userId = User::find($request->input('id'));
        $nuevoRolNombre = $request->input('rol');
        //compruebo que tengo rol de super-admin
        if (!UserController::hasRole('super-admin') && $nuevoRolNombre == 'super-admin') {
            $error = "No tienes permisos para asignar ese Rol";
            $userId = User::with('roles')->find($userId->id);
            
            return redirect()->route('showUser', ['id' => $request->input('id'), 'error' => $error]);
        }
        if ($nuevoRolNombre != 'normal') {
            $nuevoRolId = Role::where('role_name', $nuevoRolNombre)->first();
            if ($nuevoRolId) {
                DB::table('role_emp')->insert([
                    'role_id' => $nuevoRolId->id,
                    'user_id' => $userId->id
                ]);
                $exito = "Rol cambiado con éxito";
            } else {
                $error = "Ha habido un problema al asignar el nuevo Rol";
            }
        } else {
            DB::table('role_emp')->where('user_id', $userId->id)->delete();
            $exito = "Rol cambiado con éxito";
        }

        return redirect()->route('showUser', ['id' => $request->input('id'), 'error' => $error, 'exito' => $exito]);
        //$selectedUser = User::with('roles')->find($request->input('id'));
        //return Inertia::render('Usuario/PerfilUsuario', ['selectedUser' => $selectedUser, 'error' => $error]);
    }
}
