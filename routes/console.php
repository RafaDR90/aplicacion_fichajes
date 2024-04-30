<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\ComprobarAsistencia;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Schedule::command(ComprobarAsistencia::class)->everyMinute();
//Artisan::call('app:comprobar-asistencia');
//php artisan schedule:run