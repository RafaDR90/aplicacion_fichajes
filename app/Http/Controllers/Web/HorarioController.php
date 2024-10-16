<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Horario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use Diglactic\Breadcrumbs\Breadcrumbs;

use Illuminate\Support\Facades\Log;

class HorarioController extends Controller
{
    /**
     * Muestra la vista de gestión de horarios.
     *
     * @return \Inertia\Response
     */
    public function gestionHorarios()
    {
        $breadcrumbs = Breadcrumbs::generate('horarios');

        $horarios = Horario::withTrashed()->orderBy('deleted_at', 'asc')->get();
        
        return Inertia::render('Horario/VistaHorarios', ['horarios' => $horarios, 'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Muestra la vista para crear un nuevo horario.
     *
     * @return \Inertia\Response
     */
    public function newHorario()
    {
        return Inertia::render('Horario/NuevoHorario', ['breadcrumbs' => Breadcrumbs::generate('newHorario')]);
    }

    /**
     * Edita un horario existente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function editaHorario(Request $request, $id)
    {
        $horario = Horario::withTrashed()->find($id);

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
            $breadcrumbs = Breadcrumbs::generate('editHorario');
            return Inertia::render('Horario/NuevoHorario', ['errores' => $errores, 'horario' => $horario, 'breadcrumbs' => $breadcrumbs]);
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
        ) {
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
            $horarios = Horario::withTrashed()->orderBy('deleted_at', 'asc')->get();
            $breadcrumbs = Breadcrumbs::generate('horarios');

            return Inertia::render('Horario/VistaHorarios', ['exito' => $exito, 'horarios' => $horarios, 'breadcrumbs' => $breadcrumbs]);
        } else {
            $errores = ['error' => ['No se ha modificado ningun campo']];
            $breadcrumbs = Breadcrumbs::generate('editHorario');
            return Inertia::render('Horario/NuevoHorario', ['errores' => $errores, 'horario' => $horario, 'breadcrumbs' => $breadcrumbs??null]);
        }
    }

    /**
     * Crea un nuevo horario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
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
            $breadcrumbs = Breadcrumbs::generate('newHorario');
            return Inertia::render('Horario/NuevoHorario', ['errores' => $errores, 'breadcrumbs' => $breadcrumbs]);
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
        $horarios = Horario::withTrashed()->orderBy('deleted_at', 'asc')->get();
        $breadcrumbs = Breadcrumbs::generate('horarios');
        return Inertia::render('Horario/VistaHorarios', ['exito' => $exito, 'horarios' => $horarios, 'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Borra un horario existente.
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function borrarHorario($id)
    {
        $horario = Horario::find($id);
        if (!$horario) {
            return $this->gestionHorarios();
        }

        //borra horarios de la tabla intermedia
        $horario->users()->detach();

        $horario->delete();
        $exito = 'Horario ' . $horario->nombre . ' borrado correctamente';
        $horarios = Horario::withTrashed()->orderBy('deleted_at', 'asc')->get();
        $breadcrumbs = Breadcrumbs::generate('horarios');
        return Inertia::render('Horario/VistaHorarios', ['exito' => $exito, 'horarios' => $horarios, 'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Restaura un horario borrado.
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function restaurarHorario($id)
    {
        $horario = Horario::withTrashed()->find($id);
        $horario->restore();
        $exito = 'Horario ' . $horario->nombre . ' restaurado correctamente';
        $horarios = Horario::withTrashed()->orderBy('deleted_at', 'asc')->get();
        $breadcrumbs = Breadcrumbs::generate('horarios');
        return Inertia::render('Horario/VistaHorarios', ['exito' => $exito, 'horarios' => $horarios, 'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Muestra la vista para editar un horario.
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function vistaEditaHorario($id)
    {
        $horario = Horario::withTrashed()->orderBy('deleted_at', 'asc')->find($id);
        $breadcrumbs = Breadcrumbs::generate('editHorario');
        return Inertia::render('Horario/NuevoHorario', ['horario' => $horario, 'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Asigna un horario a un usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function asignarHorario(Request $request)
    {

        $newHhorario = Horario::find($request->horario_id);
        //usuario con horarios
        $user = User::with('horarios')
        ->find($request->idUser); 

        if (!$newHhorario || !$user) {
            $error = 'Ha habido un problema al asignar el horario';
            return Redirect::route('showUser', ['id' => $request->idUser, 'error' => $error]);
        }

        //compruebo si el horario ya esta asignado
        $horarioAsignado = $user->horarios()->where('id_horario', $newHhorario->id)->first();
        if ($horarioAsignado) {
            $error = 'El horario ya está asignado';
            return Redirect::route('showUser', ['id' => $request->idUser, 'error' => $error]);
        }
        if($user->horarios->getDictionary()){
            $days = $request->selectedDays;
            //recorro los horarios del usuario y le quito los dias que tiene en $days
            foreach ($user->horarios as $horario) {
                $daysToDelete = [];
                $daysOldHorario = explode(':', $horario->pivot->dias);
                //si algun dia de daysOldHorario coincide con algun dia de $days lo guardo en $daysToDelete
                foreach ($daysOldHorario as $dayOld) {
                    if (in_array($dayOld, $days)) {
                        $daysToDelete[] = $dayOld;
                    }
                }
                //quito los dias de $daysToDelete al horario seleccionado
                $newDaysOldHorario = array_diff($daysOldHorario, $daysToDelete);
                if (empty($newDaysOldHorario)) {
                    $horario->pivot->delete();
                    continue;
                }
                $horario->pivot->dias = implode(':', $newDaysOldHorario);
                $horario->pivot->save();
            }
            //guardo los dias en el nuevo horario
            $days = implode(':', $days);
        }else{
            $days = 'L:M:X:J:V';
        }
       
        $user->horarios()->attach($newHhorario->id, ['dias' => $days]);
        $exito = 'Horario asignado correctamente';

        return Redirect::route('showUser', ['id' => $request->idUser, 'exito' => $exito]);
    }


    /**
     * Desasocia un horario de un usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function desasociarHorario(Request $request)
    {
        $user = User::find($request->id);
        $user->horarios()->detach($request->id_ubicacion);
        $exito = 'Horario desasociado correctamente';
        return redirect()->route('showUser', ['id' => $request->id, 'exito' => $exito]);
    }
}
