<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RoleEmp;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|max:100|unique:role,role_name'
        ]);
        $role = new Role([
            'role_name' => $request->role_name
        ]);
        $role->save();
        return response()->json([
            'message' => 'Rol creado correctamente'],
            201);
    }

    public function asignaRolToUser(Request $request)
    {
        //comprueba que el usuario exista y que el rol exista y no este asignado al usuario
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'role_id' => 'required|integer|exists:role,id',
            'role_id' => Rule::unique('role_emp')->where(function ($query) use ($request) {
                return $query->where('user_id', $request->user_id);
            })
        ]);
        $roleEmp = new RoleEmp([
            'user_id' => $request->user_id,
            'role_id' => $request->role_id
        ]);
        $roleEmp->save();
        return response()->json([
            'message' => 'Rol asignado correctamente'],
            201);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id'=> 'required|integer|exists:role,id'
        ]);
        $role = Role::find($request->id);
        if(!$role){
            return response()->json([
                'message' => 'Rol no encontrado'],
                404);
        }
        if($role->role_name == 'admin' or $role->role_name == 'super-admin'){
            return response()->json([
                'message' => 'No se pueden eliminar los roles admin y super-admin'],
                400);
        }
        //borra todas las filas que contengan la id del rol en la tabla role_emp
        RoleEmp::where('role_id', $role->id)->delete();
        
        $role->delete();
        return response()->json([
            'message' => 'Rol eliminado correctamente'],
            200);
    }

    public function showRolesUserId(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);
        $roles = RoleEmp::where('user_id', $request->user_id)->get();
        foreach($roles as $role){
            $role->role_name = Role::find($role->role_id)->role_name;
        }
        return response()->json($roles, 200);
    }

    public function deleteRoleFromUser(Request $request)
    {
        //no te puedes quitar el rol a ti mismo
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'role_id' => 'required|integer|exists:role,id'
        ]);
        if($request->user_id == auth()->user()->id){
            return response()->json([
                'message' => 'No te puedes quitar el rol a ti mismo'],
                400);
        }
        
        $roleEmp = RoleEmp::where('user_id', $request->user_id)->where('role_id', $request->role_id)->first();
        if(!$roleEmp){
            return response()->json([
                'message' => 'El usuario no tiene asignado ese rol'],
                404);
        }
        $roleEmp->delete();
        return response()->json([
            'message' => 'Rol eliminado del usuario correctamente'],
            200);
    }

}
