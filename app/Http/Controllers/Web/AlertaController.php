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
        $filtro = $request->input('filter', '');
        //si filtro no esta vacio busco las alertas que contengan el filtro
        if ($filtro) {
            $alertas = Alerta::where('leido', 0)->where('tipo', 'like', '%' . $filtro . '%')->with('user');
        } else {
            $alertas = Alerta::where('leido', 0)->with('user');
        }

        $alertas = $alertas->paginate(10);

        return Inertia::render('Alertas/VistaAlertas', [
            'alertas' => $alertas,
            'filter' => $filtro,

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        die('create');
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
