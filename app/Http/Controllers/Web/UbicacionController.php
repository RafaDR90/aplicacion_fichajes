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

    public function denyUbicacion(Request $request)
    {
        $alerta = Alerta::find($request->idAlert);
        $alerta->leido = 1;
        $alerta->save();
    }

    public function toggleRequiereUbicacion(Request $request)
    {
        $user = User::find($request->idUser);
        $user->requiere_ubicacion = $request->requiere_ubicacion;
        $user->save();

        $user = User::with('roles')->find($request->idUser);
        return Inertia::render('Usuario/PerfilUsuario', ['selectedUser' => $user]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Ubicacion $ubicacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ubicacion $ubicacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ubicacion $ubicacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ubicacion $ubicacion)
    {
        //
    }
}
