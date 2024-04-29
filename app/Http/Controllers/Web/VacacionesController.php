<?php

namespace App\Http\Controllers\Web;

use App\Models\Vacaciones;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use App\Models\Alerta;
use Illuminate\Support\Facades\Date;
use App\Models\DiasVacaciones;

class VacacionesController extends Controller
{

    /**
     * Cancela las vacaciones no aprobadas solicitadas por el usuario.
     * 
     * @return \Inertia\Response
     */
    public function cancelarVacacionesSolicitadas()
    {
        $vacaciones = Vacaciones::where('user_id', auth()->user()->id)
            ->where('aprobada', 0)
            ->get();

        if ($vacaciones) {
            foreach ($vacaciones as $vacacion) {
                $vacacion->delete();
            }
            $diasDisponibles = auth()->user()->diasVacaciones;
            $diasDisponibles->dias_disponibles = $diasDisponibles->dias_disponibles + count($vacaciones);
            $diasDisponibles->save();

            //Borro la alerta creada
            Alerta::where('user_id', auth()->user()->id)->where('tipo', 'vacaciones')->delete();
        }

        return Redirect::route('solicitud', ['exito' => 'Solicitud de vacaciones cancelada con exito', 'vista' => 'vacaciones']);
    }

    /**
     * Obtiene las vacaciones del usuario autenticado y los días disponibles.
     * Si el usuario no tiene días de vacaciones disponibles, se crean con un valor predeterminado de 30.
     *
     * @return array Un array asociativo que contiene las vacaciones del usuario y los días disponibles.
     */
    public static function obtieneVacaciones()
    {
        //devuelvo las vacaciones del usuario junto con los dias disponibles
        $vacaciones = Vacaciones::where('user_id', auth()->user()->id)->get();
        //obtengo dias de vacaciones, si no existen los creo
        $diasDisponibles = auth()->user()->diasVacaciones;
        if (!$diasDisponibles) {
            $newVacaciones = new DiasVacaciones();
            $newVacaciones->dias_disponibles = 30;
            $newVacaciones->user_id = auth()->user()->id;
            $newVacaciones->save();
            $diasDisponibles = $newVacaciones;
        }
        return ['vacaciones' => $vacaciones, 'diasDisponibles' => $diasDisponibles];
    }

    /**
     * Maneja la solicitud de vacaciones del usuario.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function solicitudVacaciones(Request $request)
    {
        $dias = $request->input('dias');
        $diasDisponibles = auth()->user()->diasVacaciones->dias_disponibles;

        if ($diasDisponibles < count($dias)) {
            //si los dias solicitados son mayores a los dias disponibles retorno un error a la vista
            return Redirect::route('solicitud', ['error' => 'Error al solicitar vacaciones, no tienes suficientes dias disponibles', 'vista' => 'vacaciones']);
        }

        //si observations no esta vacio lo guardo en variable
        $observations = null;
        if ($request->input('observations') && $request->input('observations') != ''){
            $observations = $request->input('observations');
        }

        if (!SolicitudController::creaSolicitud('vacaciones', 'Solicitud de vacaciones', $dias, auth()->user()->id, $observations)) {
            //si no se ha creado la alerta retorno un error a la vista
            return Redirect::route('solicitud', ['error' => 'Error al solicitar vacaciones, ya tienes una solicitud de vacaciones pendiente', 'vista' => 'vacaciones']);
        }
        try {
            //guardo en la bd las vacaciones
            foreach ($dias as $dia) {
                $vacaciones = new Vacaciones();
                $vacaciones->fecha = $dia;
                $vacaciones->user_id = auth()->user()->id;
                $vacaciones->save();
            }
            //actualizo los dias disponibles
            $diasDisponibles = auth()->user()->diasVacaciones;
            $diasDisponibles->dias_disponibles = $diasDisponibles->dias_disponibles - count($dias);
            $diasDisponibles->save();
        } catch (\Exception $e) {
            //borro la alerta creada
            Alerta::where('user_id', auth()->user()->id)->where('tipo', 'vacaciones')->delete();
            return Redirect::route('solicitud', ['error' => $e->getMessage(), 'vista' => 'vacaciones']);
        }
        return Redirect::route('solicitud', ['exito' => 'Solicitud de vacaciones realizada con exito', 'vista' => 'vacaciones']);
    }

    /**
     * Actualiza el recurso especificado en el almacenamiento.
     *
     * @param  Request  $request
     * @param  Vacaciones  $vacaciones
     * @return void
     */
    public function update(Request $request, Vacaciones $vacaciones)
    {
        //obtengo datos con idAlert
        $idAlert = $request->input('idAlert');
        $alerta = Alerta::find($idAlert);
        $iduser = $request->input('idUser');

        //obtengo las vacaciones de idUser
        $vacaciones = Vacaciones::where('user_id', $iduser)->get();
        //actualizo las vacaciones
        foreach ($vacaciones as $vacacion) {
            $vacacion->aprobada = 1;
            $vacacion->save();
        }
        //actualizo la alerta
        $alerta->leido = 1;
        $alerta->save();
    }

    /**
     * Maneja la denegación de las vacaciones del usuario.
     *
     * @param  Request  $request
     * @return void
     */
    public function deniegaVacaciones(Request $request)
    {
        $idAlert = $request->input('idAlert');
        $alerta = Alerta::find($idAlert);
        $iduser = $request->input('idUser');
        $vacaciones = Vacaciones::where('user_id', $iduser)
            ->where('aprobada', 0)
            ->get();
        foreach ($vacaciones as $vacacion) {
            $vacacion->delete();
        }
        $alerta->leido = 1;
        $alerta->save();

        //devuelvo los dias de vacaciones
        $diasDisponibles = auth()->user()->diasVacaciones;
        $diasDisponibles->dias_disponibles = $diasDisponibles->dias_disponibles + count($vacaciones);
        $diasDisponibles->save();
    }
}
