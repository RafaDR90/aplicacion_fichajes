<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AutomatizacionController extends Controller
{
    public function cierraFichajes()
    {
        Artisan::call('app:comprobar-asistencia');    
    }
}
