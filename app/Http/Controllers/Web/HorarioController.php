<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Horario;

class HorarioController extends Controller
{
    public function gestionHorarios()
    {
        return Inertia::render('Horario/VistaHorarios');
    }

    public function newHorario()
    {
        return Inertia::render('Horario/NuevoHorario');
    }

    public function creaHorario(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|min:3|unique:horarios,nombre',
            'hora_entrada' => 'required|before:hora_salida|date_format:H:i',
            'hora_salida' => 'required|after:hora_entrada|date_format:H:i|different:hora_entrada',
            'descanso_salida' => 'required|after:hora_entrada|date_format:H:i',
            'descanso_entrada' => 'required|after:descanso_salida|date_format:H:i',
            'libre' => 'int|min:15|max:120',
            'tipo' => 'required|in:partido,completo,flexible',
            'totalHoras' => 'int|min:4|max:12'
        ]);
        die('todo validado');

        $horario = new Horario();
        $horario->nombre = $request->nombre;
        $horario->hora_entrada = $request->hora_entrada;
        $horario->hora_salida = $request->hora_salida;
        $horario->descanso_salida = $request->descanso_salida;
        $horario->descanso_entrada = $request->descanso_entrada;
        $horario->libre = $request->libre;
        $horario->tipo = $request->tipo;
        $horario->totalHoras = $request->totalHoras;
        $horario->save();

        return redirect()->route('horarios');
    }
}
