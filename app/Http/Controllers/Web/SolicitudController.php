<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Alerta;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SolicitudController extends Controller
{
    
    /**
     * Muestra la vista de solicitud. Si la vista es 'vacaciones', obtiene las vacaciones.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        //si vista es vacaciones llamo a la funcion de obtenervacaciones de vacacionesController
        if ($request->vista == 'vacaciones') {
            $vacaciones = VacacionesController::obtieneVacaciones();

            return Inertia::render('Solicitud/VistaSolicitud', ['vacaciones' => $vacaciones, 'error' => $request->error, 'exito' => $request->exito]);
        }
        return Inertia::render('Solicitud/VistaSolicitud');
    }

    /**
     * Crea una solicitud de alerta. Si ya existe una alerta del mismo tipo para el usuario, retorna false.
     *
     * @param  string  $tipo
     * @param  string  $mensaje
     * @param  array   $datos
     * @param  int     $userId
     * @return bool
     */
    public static function creaSolicitud($tipo, $mensaje, $datos, $userId)
    {
        //obtengo las alertas del userId del tipo $tipo y si ya hay una creada retorno false
        $alerta = Alerta::where('user_id', $userId)
        ->where('tipo', $tipo)
        ->where('leido', 0)
        ->first();

        if ($alerta) {
            return false;
        }
        try {
            $alerta = new Alerta();
            $alerta->tipo = $tipo;
            $alerta->mensaje = $mensaje;
            $alerta->datos = $datos;
            $alerta->user_id = $userId;
            $alerta->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

}
