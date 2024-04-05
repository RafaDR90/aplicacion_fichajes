<?php

namespace App\Http\Controllers\Web;

use App\Models\Vacaciones;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;


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
