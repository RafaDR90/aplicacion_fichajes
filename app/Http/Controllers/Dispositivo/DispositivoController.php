<?php

namespace App\Http\Controllers\Dispositivo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dispositivo;

class DispositivoController extends Controller
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
        $request->validate([
            'identificador_dispositivo' => 'required|string|max:255',
            'user_id' => 'required|integer'
        ]);
        $dispositivo = new Dispositivo([
            'identificador_dispositivo' => $request->identificador_dispositivo,
            'user_id' => $request->user_id
        ]);
        $dispositivo->save();
        return response()->json([
            'message' => 'Dispositivo creado correctamente'],
            201);
    }

    public function showDispositivosUserId(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer'
        ]);
        
        $dispositivos = Dispositivo::where('user_id', $request->user_id)->get();
        return response()->json($dispositivos, 200);
    }

    public function showDispositivos()
    {
        $userId = auth()->user()->id;
        $dispositivos = Dispositivo::where('user_id', $userId)->get();
        return response()->json($dispositivos, 200);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id'=> 'required|integer'
        ]);
        $disp = Dispositivo::find($request->id);
        if(!$disp){
            return response()->json([
                'message' => 'Dispositivo no encontrado'],
                404);
        }
        $disp->delete();
        return response()->json([
            'message' => 'Dispositivo eliminado correctamente'],
            200);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }
}
