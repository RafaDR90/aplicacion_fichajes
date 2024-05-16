<?php

namespace App\Services;

use GuzzleHttp\Client;

class LocationService
{
    public static function getLocation()
    {
        $ip = request()->ip();
        $ip = request()->getClientIp();
        $ip =$_SERVER['REMOTE_ADDR'];
        var_dump('asdasd');
        dd($_SERVER['REMOTE_ADDR']);

        //BORRAR ESTO CUANDO SE SUBA A PRODUCCION
        $ip = '154.62.41.89';
        $client = new Client();
        $response = $client->request('GET', 'http://ip-api.com/json/' . $ip);
        return json_decode($response->getBody()->getContents(),true);
    }
}