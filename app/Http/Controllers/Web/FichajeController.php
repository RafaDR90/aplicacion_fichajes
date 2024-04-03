<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use GuzzleHttp\Client;

class FichajeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Fichaje/VistaFichaje');
    }
    public function fichar(Request $request)
    {
        $ip = $request->ip();

        //BORRAR ESTO CUANDO SE SUBA A PRODUCCION
        $ip = '154.62.41.89';

        $client = new Client(); //esto es Guzzle
        $response = $client->request('GET', 'http://ip-api.com/json/' . $ip);
        $response = json_decode($response->getBody()->getContents());
        foreach ($response as $key => $value) {
            var_dump($key . ' => ' . $value . "<br>");
        }
        
        die();
    }
}
