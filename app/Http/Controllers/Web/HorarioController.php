<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Horario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;

use Illuminate\Support\Facades\Log;

class HorarioController extends Controller
{
    public function gestionHorarios()
    {
        $horarios = Horario::all();
        return Inertia::render('Horario/VistaHorarios', ['horarios' => $horarios]);
    }

    public function newHorario()
    {
        return Inertia::render('Horario/NuevoHorario');
    }

    public function editaHorario(Request $request, $id)
    {
        $horario = Horario::find($id);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|min:3|unique:horarios,nombre,' . $id,
            'tipo' => 'required|in:partido,continuo,flexible',
            'hora_entrada' => 'required_if:tipo,partido,continuo|date_format:H:i|before:hora_salida|nullable',
            'hora_salida' => 'required_if:tipo,partido,continuo|after:hora_entrada|date_format:H:i|different:hora_entrada|nullable',
            'descanso_salida' => 'required_if:tipo,partido|after:hora_entrada|date_format:H:i|nullable',
            'descanso_entrada' => 'required_if:tipo,partido|after:descanso_salida|date_format:H:i|nullable',
            'libre' => 'required_if:tipo,partido,continuo|int|min:15|max:120|nullable',
            'totalHoras' => 'required|int|min:4|max:12'
        ]);

        if ($validator->fails()) {
            $errores = $validator->errors();
            return Inertia::render('Horario/NuevoHorario', ['errores' => $errores, 'horario' => $horario]);
        }

        //si se ha cambiado algun campo edita el horario de lo contrario devuelve errores['No se ha modificado ningun campo']
        if (
            $horario->nombre != $request->nombre ||
            $horario->tipo != $request->tipo ||
            date('H:i', strtotime($horario->hora_entrada)) != date('H:i', strtotime($request->hora_entrada)) ||
            date('H:i', strtotime($horario->hora_salida)) != date('H:i', strtotime($request->hora_salida)) ||
            date('H:i', strtotime($horario->descanso_salida)) != date('H:i', strtotime($request->descanso_salida)) ||
            date('H:i', strtotime($horario->descanso_entrada)) != date('H:i', strtotime($request->descanso_entrada)) ||
            $horario->libre != $request->libre ||
            $horario->total_horas != $request->totalHoras
        ){
            $horario->nombre = $request->nombre;
            $horario->hora_entrada = $request->hora_entrada;
            $horario->hora_salida = $request->hora_salida;
            $horario->descanso_salida = $request->descanso_salida;
            $horario->descanso_entrada = $request->descanso_entrada;
            $horario->libre = $request->libre;
            $horario->tipo = $request->tipo;
            $horario->total_horas = $request->totalHoras;
            $horario->save();

            $exito = 'Horario editado correctamente';
            $horarios = Horario::all();
            return Inertia::render('Horario/VistaHorarios', ['exito' => $exito, 'horarios' => $horarios]);
        } else {
            $errores = ['error' => ['No se ha modificado ningun campo']];
            return Inertia::render('Horario/NuevoHorario', ['errores' => $errores, 'horario' => $horario]);
        }
    }

    public function creaHorario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|min:3|unique:horarios,nombre',
            'tipo' => 'required|in:partido,continuo,flexible',
            'hora_entrada' => 'required_if:tipo,partido,continuo|date_format:H:i|before:hora_salida|nullable',
            'hora_salida' => 'required_if:tipo,partido,continuo|after:hora_entrada|date_format:H:i|different:hora_entrada|nullable',
            'descanso_salida' => 'required_if:tipo,partido|after:hora_entrada|date_format:H:i|nullable',
            'descanso_entrada' => 'required_if:tipo,partido|after:descanso_salida|date_format:H:i|nullable',
            'libre' => 'required_if:tipo,partido,continuo|int|min:15|max:120|nullable',
            'totalHoras' => 'required|int|min:4|max:12'
        ]);

        if ($validator->fails()) {
            $errores = $validator->errors();
            return Inertia::render('Horario/NuevoHorario', ['errores' => $errores]);
        }

        $horario = new Horario();
        $horario->nombre = $request->nombre;
        $horario->hora_entrada = $request->hora_entrada;
        $horario->hora_salida = $request->hora_salida;
        $horario->descanso_salida = $request->descanso_salida;
        $horario->descanso_entrada = $request->descanso_entrada;
        $horario->libre = $request->libre;
        $horario->tipo = $request->tipo;
        $horario->total_horas = $request->totalHoras;
        $horario->save();

        $exito = 'Horario creado correctamente';
        $horarios = Horario::all();
        return Inertia::render('Horario/VistaHorarios', ['exito' => $exito, 'horarios' => $horarios]);
    }

    public function borrarHorario($id)
    {
        $horario = Horario::find($id);
        //borra horarios de la tabla intermedia
        $horario->users()->detach();

        $horario->delete();
        $exito = 'Horario ' . $horario->nombre . ' borrado correctamente';
        $horarios = Horario::all();
        return Inertia::render('Horario/VistaHorarios', ['exito' => $exito, 'horarios' => $horarios]);
    }

    public function vistaEditaHorario($id)
    {
        $horario = Horario::find($id);
        return Inertia::render('Horario/NuevoHorario', ['horario' => $horario]);
    }

    public function asignarHorario(Request $request)
    {

        $horario = Horario::find($request->horario_id);
        $user = User::find($request->idUser);

        if (!$horario || !$user) {
            $error = 'Ha habido un problema al asignar el horario';
            return Redirect::route('showUser', ['id' => $request->idUser, 'error' => $error]);
        }

        //compruebo si el horario ya esta asignado
        $horarioAsignado = $user->horarios()->where('id_horario', $horario->id)->first();
        if ($horarioAsignado) {
            $error = 'El horario ya está asignado';
            return Redirect::route('showUser', ['id' => $request->idUser, 'error' => $error]);
        }

        $user->horarios()->attach($horario->id);
        $exito = 'Horario asignado correctamente';

        return Redirect::route('showUser', ['id' => $request->idUser, 'exito' => $exito]);
    }

    public function desasociarHorario(Request $request)
    {
        $user = User::find($request->id);
        $user->horarios()->detach($request->id_ubicacion);
        $exito = 'Horario desasociado correctamente';
        return redirect()->route('showUser', ['id' => $request->id, 'exito' => $exito]);

    }
}
