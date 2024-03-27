<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Resources\UserResource;


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

        $query = User::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('apellidos', 'like', '%' . $search . '%');
        }
        if(!empty($sortField))
        $query->orderBy($sortField, 'asc');

        $users = $query->paginate(4);

        return Inertia::render('Usuario/VistaUsuarios', ['users' => $users, 'search' => $search, 'sortField' => $sortField]);
    }
}
/* $posts = Post::all();
    return Inertia::render('Post/Index', ['posts' => $posts]); */