<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


class UserController extends Controller
{

    public function index()
    {
        $authenticatedSessionController = new AuthenticatedSessionController();
        return $authenticatedSessionController->create();
    }

    public function showUsers()
    {
        $users = User::all();
        return Inertia::render('Usuario/VistaUsuarios', ['users' => $users]);
    }
}
/* $posts = Post::all();
    return Inertia::render('Post/Index', ['posts' => $posts]); */