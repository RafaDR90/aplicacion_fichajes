<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use App\Models\Alerta;
use App\Models\User;
use Inertia\Inertia;


class UbicacionController extends Controller
{

    /**
     * Añade una ubicación a partir de una alerta.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function addUbicacion(Request $request)
    {
        //obtengo datos de alerta con idAlert
        $alerta = Alerta::find($request->idAlert);
        //creo nueva ubicacion
        $ubicacion = new Ubicacion();
        $ubicacion->latitud = $alerta->datos['lat'];
        $ubicacion->longitud = $alerta->datos['lon'];
        $ubicacion->ciudad = $alerta->datos['city'];
        $ubicacion->pais = $alerta->datos['countryCode'];
        $ubicacion->cp = $alerta->datos['zip'];

        $user = User::find($request->idUser);

        //compruebo que esta ubicacion no existe en la bd
        $ubicacionExistente = Ubicacion::where('latitud', $ubicacion->latitud)
            ->where('longitud', $ubicacion->longitud)
            ->where('ciudad', $ubicacion->ciudad)
            ->where('pais', $ubicacion->pais)
            ->where('cp', $ubicacion->cp)
            ->first();

        //si existe, compruebo si ya esta asociada al usuario
        if ($ubicacionExistente) {
            $ubicacionAsociada = $user->ubicacion()->where('ubicacion_id', $ubicacionExistente->id)->first();
            //si ya esta asociada devuelvo a la vista anulando la alerta
            if ($ubicacionAsociada) {
                $alerta->leido = 1;
                $alerta->save();
                return;
            }
            //si no esta asociada le cambio el id a la ubicacion creada por el id de la existente
            $ubicacion->id = $ubicacionExistente->id;

            //en caso de que no exista la ubicacion, la guardo
        } else {
            $ubicacion->save();
        }

        //asocio la ubicacion al usuario
        $user->ubicacion()->attach($ubicacion->id);

        //marco alerta como leida
        $alerta->leido = 1;
        $alerta->save();
    }

    /**
     * Marca una alerta como leída sin añadir la ubicación.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function denyUbicacion(Request $request)
    {
        $alerta = Alerta::find($request->idAlert);
        $alerta->leido = 1;
        $alerta->save();
    }

    /**
     * Cambia la preferencia del usuario sobre si requiere ubicación o no.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleRequiereUbicacion(Request $request)
    {
        $user = User::find($request->idUser);
        $user->requiere_ubicacion = $request->requiere_ubicacion;
        $user->save();

        //creo exito dependiendo de si se ha activado o desactivado
        $exito = $request->requiere_ubicacion ? 'Se notificará su ubicación la próxima vez que inicie sesión.' : 'La ubicación no es requerida.';

        //llamo a la ruta showUser mandandole el idUser y el exito
        return redirect()->route('showUser', ['id' => $request->idUser, 'exito' => $exito]);
    }

    /**
     * Desasocia una ubicación del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function desasociarUbicacion(Request $request)
    {
        $user = User::find($request->id);
        $user->ubicacion()->detach($request->id_ubicacion);

        $user = User::with(['roles', 'ubicacion'])->find($user->id);
        $exito = 'Ubicación desasociada con éxito';
        return redirect()->route('showUser', ['id' => $request->id, 'exito' => $exito]);
    }
}
