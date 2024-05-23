<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Models\Alerta;

class AutomatizacionController extends Controller
{
    public function cierraFichajes()
    {
        Artisan::call('app:comprobar-asistencia');
    }

    public static function obtieneNotificaciones()
    {
        //obtiene la cantidad de notificaciones de la BD no leidas
        $notificaciones = Alerta::where('leido', 0)->whereHas('user')->count();
        return response()->json(['notificacionesCount' => $notificaciones]);
    }
}
