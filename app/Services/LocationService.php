<?php

namespace App\Services;

use GuzzleHttp\Client;

class LocationService
{
    public static function getLocation()
    {
        $ip = null;
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        //whether ip is from the proxy  
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        //whether ip is from the remote address  
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        //BORRAR ESTO CUANDO SE SUBA A PRODUCCION
        //$ip = '154.62.41.89';
        $client = new Client();
        $response = $client->request('GET', 'http://ip-api.com/json/' . $ip);
        return json_decode($response->getBody()->getContents(), true);
    }
}
