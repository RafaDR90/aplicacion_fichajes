<?php

namespace App\Http\Controllers\Web;

use App\Models\Vacaciones;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use App\Models\Alerta;
use Illuminate\Support\Facades\Date;

class VacacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public static function obtieneVacaciones()
    {
        //devuelvo las vacaciones del usuario
        return $vacaciones = Vacaciones::where('user_id', auth()->user()->id)->get();
        

    }

    public function solicitudVacaciones (Request $request)
    {
        $dias = $request->input('dias');

        if(!SolicitudController::creaSolicitud('vacaciones', 'Solicitud de vacaciones', $dias, auth()->user()->id)){
            //si no se ha creado la alerta retorno un error a la vista
            return Redirect::route('solicitud',['error' => 'Error al solicitar vacaciones, ya tienes una solicitud de vacaciones pendiente','vista' => 'vacaciones']);
        }
        try{
            //guardo en la bd las vacaciones
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

    public function update(Request $request, Vacaciones $vacaciones)
    {
        //obtengo datos con idAlert
        $idAlert = $request->input('idAlert');
        $alerta = Alerta::find($idAlert);
        $iduser=$request->input('idUser');

        //obtengo las vacaciones de idUser
        $vacaciones = Vacaciones::where('user_id', $iduser)->get();
        //actualizo las vacaciones
        foreach($vacaciones as $vacacion){
            $vacacion->aprobada = 1;
            $vacacion->save();
        }
        //actualizo la alerta
        $alerta->leido = 1;
        $alerta->save();
        return Redirect::route('alertas');

        dd($alerta);die();
    }

    public function deniegaVacaciones(Request $request)
    {
        $idAlert = $request->input('idAlert');
        $alerta = Alerta::find($idAlert);
        $iduser=$request->input('idUser');
        $vacaciones = Vacaciones::where('user_id', $iduser)
        ->where('aprobada', 0)
        ->get();
        foreach($vacaciones as $vacacion){
            $vacacion->delete();
        }
        $alerta->leido = 1;
        $alerta->save();
        return Redirect::route('alertas');
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
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vacaciones $vacaciones)
    {
        //
    }
}
