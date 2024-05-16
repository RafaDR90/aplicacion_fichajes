<?php

namespace App\Services;

use GuzzleHttp\Client;

class LocationService
{
    public static function getLocation()
    {
        $ip = request()->ip();
        $ip = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));

        //BORRAR ESTO CUANDO SE SUBA A PRODUCCION
        //$ip = '154.62.41.89';
var_dump($ip);die();
        $client = new Client();
        $response = $client->request('GET', 'http://ip-api.com/json/' . $ip);
        return json_decode($response->getBody()->getContents(),true);
    }
}