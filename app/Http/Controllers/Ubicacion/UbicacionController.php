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
        return response()->json(Ubicacion::all(), 200);
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
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:ubicacion,id'
        ]);
        $ubicacion = Ubicacion::find($request->id);
        

        try {
            $ubicacion->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la ubicacion, solo se pueden borrar ubicaciones que no se han usado'],
                500);
        }
        return response()->json([
            'message' => 'Ubicacion eliminada correctamente'],
            200);
    }
    
}
