<?php

namespace App\Http\Controllers\Web;

use App\Models\Vacaciones;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use App\Models\Alerta;



class VacacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public static function obtieneVacaciones()
    {
        //creo un array falso de vacaciones
        $vacaciones = [
            [
                'id' => 1,
                'fecha' => '2021-06-01',
                'estado' => 'pendiente'
            ],
            [
                'id' => 2,
                'fecha' => '2021-06-02',
                'estado' => 'pendiente'
            ],
           
        ];
        return $vacaciones;
    }

    public function solicitudVacaciones (Request $request)
    {
        $dias = $request->input('dias');

        if(!SolicitudController::creaSolicitud('vacaciones', 'Solicitud de vacaciones', $dias, auth()->user()->id)){
            //si no se ha creado la alerta retorno un error a la vista
            return Redirect::route('solicitud',['error' => 'Error al solicitar vacaciones','vista' => 'vacaciones']);
        }
        try{
            //guardo en la bd las vacaciones haciendo un bucle a los dias
            foreach($dias as $dia){
                $vacaciones = new Vacaciones();
                $vacaciones->fecha = $dia;
                $vacaciones->user_id = auth()->user()->id;
                $vacaciones->save();
            }
        }catch(\Exception $e){
            //borro la alerta creada
            Alerta::where('user_id', auth()->user()->id)->where('tipo', 'vacaciones')->delete();
            return Redirect::route('solicitud',['error' => $e->getMessage() ,'vista' => 'vacaciones']);
        }
        return Redirect::route('solicitud',['exito' => 'Solicitud de vacaciones realizada con exito','vista' => 'vacaciones']);

        
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(Vacaciones $vacaciones)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vacaciones $vacaciones)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vacaciones $vacaciones)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vacaciones $vacaciones)
    {
        //
    }
}
