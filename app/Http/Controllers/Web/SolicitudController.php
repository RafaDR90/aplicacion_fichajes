<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Alerta;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SolicitudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //si vista es vacaciones llamo a la funcion de obtenervacaciones de vacacionesController
        //var_dump($request->vista);die;
        if ($request->vista == 'vacaciones') {
            $vacaciones = VacacionesController::obtieneVacaciones();
            return Inertia::render('Solicitud/VistaSolicitud', ['vacaciones' => $vacaciones, 'error' => $request->error, 'exito' => $request->exito]);
        }
        return Inertia::render('Solicitud/VistaSolicitud');
    }

    /**
     * Show the form for creating a new resource.
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Alerta $alerta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alerta $alerta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alerta $alerta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alerta $alerta)
    {
        //
    }
}
