<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Http\Controllers\Web\AlertaController;
use App\Models\Fichaje;

class ComprobarAsistencia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:comprobar-asistencia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comprueba faltas de asistencia, Falta de fichaje, Total de horas trabajadas.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //$this->empleadosAusentes();
        $this->cierraFichajes();
        $this->compruebaHorasTrabajadas();
    }

    private function empleadosAusentes()
    {
        // obtiene todos los usuarios
        $users = User::all();
        foreach ($users as $user) {
            //obtengo dias de vacaciones del usuario donde sea hoy
            $diasVacaciones = $user->Vacaciones()->where('fecha', date('Y-m-d'))->first();
            if (!$diasVacaciones) {
                //obtengo fichajes de hoy
                $fichajes = $user->fichajes()->where('created_at', 'like', date('Y-m-d') . '%')->get();
                if ($fichajes->count() == 0) {
                    //creo una alaerta con el tipo de falta de asistencia
                    $datos = [
                        'user_id' => $user->id,
                        'fecha' => date('Y-m-d'),
                    ];
                    AlertaController::create('asistencia', 'Falta de asistencia', $datos, $user->id);
                }
            }
        }
    }


    private function cierraFichajes()
    {
        $users = User::all();
        foreach ($users as $user) {
            $fichajes = $user->fichajes()->where('created_at', 'like', date('Y-m-d') . '%')->get();
            if ($fichajes->count() % 2 != 0) {
                //obtiene horario
                $horario = $user->horarios()->first();

                //crea fichaje de salida
                $nuevoFichaje = new Fichaje();
                $nuevoFichaje->user_id = $user->id;
                $nuevoFichaje->ubicacion_id = null;
                $nuevoFichaje->tipo = 'salida';
                $nuevoFichaje->created_at = date('Y-m-d') . ' ' . $horario->hora_salida;
                $nuevoFichaje->save();

                $datos = [
                    'user_id' => $user->id,
                    'fecha' => date('Y-m-d'),
                ];
                AlertaController::create('fichajeClose', 'Fichaje no cerrado', $datos, $user->id);
            }
        }
    }


    private function compruebaHorasTrabajadas()
    {
        $users = User::all();
        foreach ($users as $user) {
            $fichajes = $user->fichajes()->where('created_at', 'like', date('Y-m-d') . '%')->get();
            if ($fichajes->count() > 1) {
                $horasTrabajadas = 0;
                for ($i = 0; $i < $fichajes->count(); $i += 2) {
                    $entrada = $fichajes[$i];
                    $salida = $fichajes[$i + 1];
                    $horasTrabajadas += $entrada->created_at->diffInMinutes($salida->created_at);
                }
                //obtengo el horario del usuario
                $horario = $user->horarios()->first();
                if (($horario->total_horas * 60 - 15) > $horasTrabajadas) {
                    $horas = floor($horasTrabajadas / 60);
                    $minutos = $horasTrabajadas % 60;
                    $tiempo = sprintf("%d:%02d", $horas, $minutos);
                    $datos = [
                        'user_id' => $user->id,
                        'fecha' => date('Y-m-d'),
                        'horas_trabajadas' => $tiempo,
                        'horas_requeridas' => $horario->total_horas,
                    ];
                    AlertaController::create('horasTrabajadas', 'Horas trabajadas insuficientes', $datos, $user->id);
                }
            }
        }
    }
}
