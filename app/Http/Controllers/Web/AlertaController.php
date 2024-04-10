<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Alerta;
use Illuminate\Http\Request;
use Inertia\Inertia;


class AlertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $leidos = $request->input('leidos', false);
        $leidos = filter_var($leidos, FILTER_VALIDATE_BOOLEAN);
        $filtro = $request->input('filter', '');
        //si filtro no esta vacio busco las alertas que contengan el filtro
        if ($filtro) {
            $alertas = Alerta::where('leido', $leidos)->where('tipo', 'like', '%' . $filtro . '%')->with('user');
        } else {
            $alertas = Alerta::where('leido', $leidos)->with('user');
        }

        $alertas = $alertas->paginate(10);

        return Inertia::render('Alertas/VistaAlertas', [
            'alertas' => $alertas,
            'filter' => $filtro,
            'leidos' => $leidos

        ]);
    }

    /**
     * Crea una alerta
     */
    public static function create($tipo, $mensaje, $datos, $userId)
    {
        $alerta = new Alerta();
        $alerta->tipo = $tipo;
        $alerta->mensaje = $mensaje;
        $alerta->datos = $datos;
        $alerta->user_id = $userId;
        $alerta->save();   
    }

    public function marcarLeidaAlerta(Request $request)
    {
        $alerta = Alerta::find($request->idAlert);
        $alerta->leido = 1;
        $alerta->save();
    }
}
