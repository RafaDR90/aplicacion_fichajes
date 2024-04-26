<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redirect;
use App\Models\Fichaje;
use Illuminate\Support\Carbon;
use App\Models\Alerta;
use App\Models\Horario;
use Illuminate\Support\Facades\Date;
use App\Models\User;
use Carbon\WeekDay;
use Illuminate\Support\Facades\DB;


class FichajeController extends Controller
{
    /**
     * Muestra la vista de fichajes.
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $serverTime = Carbon::now('UTC');
        $serverTimeString = $serverTime->toJSON();

        $horario = null;
        $horarios = $request->user()->horarios;
        $weekDay = date('N');
        $weekDay = $this->getWeekDay($weekDay);
        foreach ($horarios as $key => $value) {
            $days = explode(':', $value->pivot->dias);
            if (in_array($weekDay, $days)) {
                $horario = $value;
                break;
            }
        }
        
        $fichajes = Fichaje::where('user_id', $request->user()->id)
            ->where('created_at', 'like', date('Y-m-d') . '%')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Fichaje/VistaFichaje', ['error' => $request->error, 'exito' => $request->exito, 'horario' => $horario, 'fichajes' => $fichajes, 'serverTime' => $serverTimeString]);
    }



    /**
     * Realiza el fichaje de un usuario.
     *
     * @param  Request  $request
     * @return Redirect
     */
    public function fichar(Request $request)
    {
        //muestro datetime de laravel en un vardump
        $ip = $request->ip();
        //BORRAR ESTO CUANDO SE SUBA A PRODUCCION
        $ip = '154.62.41.89';

        $client = new Client(); //creo clase de Guzzle
        $response = $client->request('GET', 'http://ip-api.com/json/' . $ip);
        $response = json_decode($response->getBody()->getContents());
        if ($response->status == 'fail') {
            return Redirect::route('fichaje', ['error' => 'Error al obtener la ubicacion']);
        }


        //obtengo usuario junto con ubicacion y horarios asignados
        $user = $request->user();
        $ubicacion = $user->ubicacion;

        //obtengo el dia de la semana en el que estoy
        $weekDay = date('N');
        $weekDay = $this->getWeekDay($weekDay);

        //obtiene todos los horarios del usuario
        $horarios = $user->horarios;

        //compruebo que el usuario tenga un horario asignado el dia de hoy
        $horario = null;
        if ($horarios->isEmpty()) {
            return Redirect::route('fichaje', ['error' => 'No tienes ningun horario asignado']);
        }
        foreach ($horarios as $key => $value) {
            $days = explode(':', $value->pivot->dias);
            if (in_array($weekDay, $days)) {
                $horario = $value;
                break;
            }
        }

        //si no existen ubicaciones retorno con error
        if ($ubicacion->isEmpty()) {
            return Redirect::route('fichaje', ['error' => 'No tienes ninguna ubicacion asignada']);
        }
        if (!$horario) {
            return Redirect::route('fichaje', ['error' => 'No tienes ningun horario asignado para hoy']);
        }
        $ubicacionPermitida = false;
        $ubicacionId = null;
        foreach ($ubicacion as $key => $value) {
            if (
                $value->latitud == $response->lat &&
                $value->longitud == $response->lon &&
                $value->cp == $response->zip &&
                $value->ciudad == $response->city &&
                $value->pais == $response->countryCode
            ) {
                $ubicacionPermitida = true;
                $ubicacionId = $value->id;
                //salgo del foreach
                break;
            }
        }
        if (!$ubicacionPermitida) {
            return Redirect::route('fichaje', ['error' => 'Ubicacion no permitida']);
        }

        //obtengo fecha actual
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');

        //$hora = date('H:i:s', mktime(10, 50, 0));

        //obtengo el ultimo fichaje del usuario de hoy
        $ultimoFichaje = Fichaje::where('user_id', $user->id)
            ->where('created_at', 'like', $fecha . '%')
            ->orderBy('created_at', 'desc')
            ->first();

        //creo un nuevo fichaje
        $nuevoFichaje = new Fichaje();
        $nuevoFichaje->user_id = $user->id;
        $nuevoFichaje->ubicacion_id = $ubicacionId;


        if (!$ultimoFichaje || !$ultimoFichaje->esDeHoy()) {
            $nuevoFichaje->tipo = 'entrada';
            //compruebo que la hora actual este en un rango de 15 minutos antes o despues de la hora de entrada
            if ($horario->tipo == 'partido' || $horario->tipo == 'continuo') {
                $horaEntrada = Carbon::parse($horario->hora_entrada);
                $horaSalida = Carbon::parse($horario->hora_salida);
                if ($hora < (clone $horaEntrada)->subMinutes(15)->format('H:i:s')) {
                    return Redirect::route('fichaje', ['error' => 'Hora de entrada no permitida, su hora de entrada es: ' . $horaEntrada->format('H:i:s')]);
                }
                if ($hora > (clone $horaEntrada)->addMinutes(15)->format('H:i:s') && $hora < (clone $horaSalida)->subMinutes(30)->format('H:i:s')) {
                    $datos = [
                        'horaFichaje' => $hora,
                        'horaEntrada' => $horaEntrada->format('H:i:s'),
                        'nombreHorario' => $horario->nombre,
                    ];
                    AlertaController::create('fichaje', 'Anomalía fichaje', $datos, $user->id);
                    $error = 'LLegada tarde, se ha creado una alerta. Su hora de entrada es: ' . $horaEntrada->format('H:i:s');
                }
            }
        } else {
            if ($ultimoFichaje->tipo == 'entrada') {
                $nuevoFichaje->tipo = 'salida';
                //si la hora actual es mayor a 15 minutos de la hora de salida retorno con error
                if ($horario->tipo == 'partido' || $horario->tipo == 'continuo') {
                    $horaSalida = Carbon::parse($horario->hora_salida);
                    if ($hora > (clone $horaSalida)->addMinutes(15)->format('H:i:s')) {
                        //no ficha, ya se fichara automaticamente y se enviara notificacion
                        return Redirect::route('fichaje', ['error' => 'Hora de salida exedida, su hora de salida es: ' . $horaSalida->format('H:i:s')]);
                    }
                }
            } else {
                $nuevoFichaje->tipo = 'entrada';
                if ($horario->tipo == 'partido' || $horario->tipo == 'continuo') {
                    $horaSalida = Carbon::parse($horario->hora_salida);
                    if ($hora > (clone $horaSalida)->subMinutes(30)->format('H:i:s') && $hora < (clone $horaSalida)->format('H:i:s')) {
                        //no ficha, ya se fichara automaticamente y se enviara notificacion
                        return Redirect::route('fichaje', ['error' => 'No puedes fichar entrada, queda poco para tu hora de salida: ' . $horaSalida->format('H:i:s')]);
                    } elseif ($hora > (clone $horaSalida)->format('H:i:s')) {
                        return Redirect::route('fichaje', ['error' => 'No puedes fichar entrada, fuera de horario.']);
                    }
                }
            }
        }

        $nuevoFichaje->save();
        $fichajes = Fichaje::where('user_id', $user->id)
            ->where('created_at', 'like', $fecha . '%')
            ->orderBy('created_at', 'desc')
            ->get();


        $serverTime = Carbon::now('UTC');
        $serverTimeString = $serverTime->toJSON();

        return Redirect::route('fichaje', ['exito' => 'Fichaje de ' . $nuevoFichaje->tipo . ' realizado con exito.', 'error' => $error ?? null, 'fichajes' => $fichajes, 'horario' => $horario ?? null, 'serverTime' => $serverTimeString]);
    }

    /**
     * Muestra los fichajes de los usuarios.
     *
     * @param  Request  $request
     * @return InertiaResponse
     */
    public function showFichajes(Request $request)
    {
        $today = date('Y-m-d');
        $day = date('Y-m-d');
        $searchName = null;
        $request->validate([
            'dateFilter' => 'nullable|date_format:Y-m-d',
            'search' => 'nullable|string'
        ]);
        if ($request->dateFilter) {
            $day = $request->dateFilter;
        }
        if ($request->search) {
            $searchName = $request->search;
        }

        //obtengo users que no tienen vacaciones

        $users = User::with('vacaciones')
            ->where(DB::raw('CONCAT(name, " ", apellidos)'), 'like', '%' . $searchName . '%')
            ->whereDoesntHave('vacaciones', function ($query) use ($day) {
                $query->where('fecha', '=', $day)
                    ->where('aprobada', '=', 1);
            })->paginate(15);


        //añado a users sus fichajes de hoy
        $coincidence = false;
        foreach ($users as $key => $user) {
            $checkins = Fichaje::where('user_id', $user->id)
                ->where('created_at', 'like', $day . '%')
                ->orderBy('created_at', 'desc')
                ->get();
            $user->setAttribute('checkins', $checkins);

            if ($checkins->count() > 0) {
                $coincidence = true;
            }
        }

        return Inertia::render('Fichaje/VistaFichajes', ['serverDate' => $today, 'users' => $users, 'coincidence' => $coincidence, 'searchDateServer' => $day, 'searchNameServer' => $searchName]);
    }


    /**
     * Obtiene la letra del dia de la semana con el numero de dia.
     *
     * @param int $weekDay el numero de dia del 1 al 7.
     * @return string La letra del dia de la semana
     */
    public function getWeekDay($weekDay)
    {
        switch ($weekDay) {
            case 1:
                return 'L';
            case 2:
                return 'M';
            case 3:
                return 'X';
            case 4:
                return 'J';
            case 5:
                return 'V';
            case 6:
                return 'S';
            case 7:
                return 'D';
        }
    }
}
