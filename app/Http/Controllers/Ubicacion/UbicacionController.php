<?php

namespace App\Http\Controllers\Ubicacion;

use App\Http\Controllers\Controller;
use App\Models\Ubicacion\Ubicacion;
use Illuminate\Http\Request;

class UbicacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //todos los campos juntos tienen que ser unicos
        $request->validate([
            'pais' => 'required|string|max:100',
            'ciudad' => 'required|string|max:100',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric'
        ]);
        if (Ubicacion::where('pais', $request->pais)
            ->where('ciudad', $request->ciudad)
            ->where('latitud', $request->latitud)
            ->where('longitud', $request->longitud)
            ->exists()) {
            return response()->json([
                'message' => 'Ubicacion ya existe'],
                409);
        }

        $ubicacion = new Ubicacion([
            'pais' => $request->pais,
            'ciudad' => $request->ciudad,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud
        ]);
        $ubicacion->save();
        return response()->json([
            'message' => 'Ubicacion creada correctamente'],
            201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ubicacion $ubicacion)
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
