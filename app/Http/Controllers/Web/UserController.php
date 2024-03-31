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
        $search = $request->input('search','');
        $sortField = $request->input('sortField', 'name'); // Default sort field is 'name'

        $query = User::with('roles');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('apellidos', 'like', '%' . $search . '%');
        }
        if(!empty($sortField))
        $query->orderBy($sortField, 'asc');

        $users = $query->paginate(15);

        return Inertia::render('Usuario/VistaUsuarios', ['users' => $users, 'search' => $search, 'sortField' => $sortField]);
    }

    public function showUser(Request $request)
    {
        $id = $request->input('id');
        $user = User::with('roles')->find($id);
        return Inertia::render('Usuario/PerfilUsuario', ['selectedUser' => $user]);
    }

    public static function hasRole($role){
        $user = Auth::user();
        $roles = $user->roles;
        foreach ($roles as $rol) {
            if($rol->role_name == $role){
                return true;
            }
        }
        return false;
    
    }

    

}
/* $posts = Post::all();
    return Inertia::render('Post/Index', ['posts' => $posts]); */