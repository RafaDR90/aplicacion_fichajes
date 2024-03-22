<?php

namespace App\Http\Controllers\WebAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;


class UserController extends Controller
{
    public function index(){
        $users = User::all();
        return Inertia::render('Usuario/Index', ['users' => $users]);
    }
}
/* $posts = Post::all();
    return Inertia::render('Post/Index', ['posts' => $posts]); */