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


class FichajeController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Fichaje/VistaFichaje', ['error' => $request->error, 'exito' => $request->exito]);
    }
    public function fichar(Request $request)
    {
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
        $horario = $user->horarios;
        //si no existen ubicaciones retorno con error
        if ($ubicacion->isEmpty()) {
            return Redirect::route('fichaje', ['error' => 'No tienes ninguna ubicacion asignada']);
        }
        if ($horario->isEmpty()) {
            return Redirect::route('fichaje', ['error' => 'No tienes ningun horario asignado']);
        }
        $ubicacionPermitida = false;
        foreach ($ubicacion as $key => $value) {
            if (
                $value->latitud == $response->lat &&
                $value->longitud == $response->lon &&
                $value->cp == $response->zip &&
                $value->ciudad == $response->city &&
                $value->pais == $response->countryCode
            ) {
                $ubicacionPermitida = true;
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

        $hora = date('H:i:s', mktime(10, 50, 0));

        //obtengo el ultimo fichaje del usuario de hoy
        $ultimoFichaje = Fichaje::where('user_id', $user->id)
            ->where('created_at', 'like', $fecha . '%')
            ->orderBy('created_at', 'desc')
            ->first();

        //creo un nuevo fichaje
        $nuevoFichaje = new Fichaje();
        $nuevoFichaje->user_id = $user->id;
        $nuevoFichaje->ubicacion_id = $ubicacion[0]->id;


        if (!$ultimoFichaje || !$ultimoFichaje->esDeHoy()) {
            $nuevoFichaje->tipo = 'entrada';
            //compruebo que la hora actual este en un rango de 15 minutos antes o despues de la hora de entrada
            if ($horario[0]->tipo == 'partido' || $horario[0]->tipo == 'continuo') {
                $horaEntrada = Carbon::parse($horario[0]->hora_entrada);
                $horaSalida = Carbon::parse($horario[0]->hora_salida);
                if ($hora < (clone $horaEntrada)->subMinutes(15)->format('H:i:s')) {
                    return Redirect::route('fichaje', ['error' => 'Hora de entrada no permitida, su hora de entrada es: ' . $horaEntrada->format('H:i:s')]);
                }
                if ($hora > (clone $horaEntrada)->addMinutes(15)->format('H:i:s')&& $hora < (clone $horaSalida)->subMinutes(30)->format('H:i:s')) {
                    $datos = [
                        'horaFichaje' => $hora,
                        'horaEntrada' => $horaEntrada->format('H:i:s'),
                        'nombreHorario' => $horario[0]->nombre,
                    ];
                    AlertaController::create('fichaje', 'Anomalía fichaje', $datos, $user->id);
                    $error='LLegada tarde, se ha creado una alerta. Su hora de entrada es: ' . $horaEntrada->format('H:i:s');
                }
            }
        } else {
            if ($ultimoFichaje->tipo == 'entrada') {
                $nuevoFichaje->tipo = 'salida';
                //si la hora actual es mayor a 15 minutos de la hora de salida retorno con error
                if ($horario[0]->tipo == 'partido' || $horario[0]->tipo == 'continuo') {
                    $horaSalida = Carbon::parse($horario[0]->hora_salida);
                    if ($hora > (clone $horaSalida)->addMinutes(15)->format('H:i:s')) {
                        //no ficha, ya se fichara automaticamente y se enviara notificacion
                        return Redirect::route('fichaje', ['error' => 'Hora de salida exedida, su hora de salida es: ' . $horaSalida->format('H:i:s')]);
                    }
                }
            } else {
                $nuevoFichaje->tipo = 'entrada';
                if ($horario[0]->tipo == 'partido' || $horario[0]->tipo == 'continuo'){
                    $horaSalida = Carbon::parse($horario[0]->hora_salida);
                    if ($hora > (clone $horaSalida)->subMinutes(30)->format('H:i:s')&& $hora < (clone $horaSalida)->format('H:i:s')){
                        //no ficha, ya se fichara automaticamente y se enviara notificacion
                        return Redirect::route('fichaje', ['error' => 'No puedes fichar entrada, queda poco para tu hora de salida: ' . $horaSalida->format('H:i:s')]);
                    }elseif($hora > (clone $horaSalida)->format('H:i:s')){
                        return Redirect::route('fichaje', ['error' => 'No puedes fichar entrada, fuera de horario.']);
                    }
                }
            }
        }

        $nuevoFichaje->save();

        return Redirect::route('fichaje', ['exito' => 'Fichaje de '.$nuevoFichaje->tipo.' realizado con exito.', 'error'=>$error ?? null]);
    }
}
