<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HorarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('horarios')->insert([
            [
             
                'nombre' => 'Basico Partido',
                'hora_entrada' => '09:00:00',
                'hora_salida' => '18:30:00',
                'descanso_salida' => '14:00:00',
                'descanso_entrada' => '15:00:00',
                'libre' => 30,
                'tipo' => 'partido',
                'created_at' => '2024-04-02 10:08:02',
                'updated_at' => '2024-04-02 13:50:29',
                'total_horas' => 8,
            ],
            [
                
                'nombre' => 'Basico Continuo',
                'hora_entrada' => '08:00:00',
                'hora_salida' => '16:30:00',
                'descanso_salida' => null,
                'descanso_entrada' => null,
                'libre' => 30,
                'tipo' => 'continuo',
                'created_at' => '2024-04-02 10:12:24',
                'updated_at' => '2024-04-02 13:50:45',
                'total_horas' => 8,
            ],
            [
                
                'nombre' => 'Basico Flexible',
                'hora_entrada' => null,
                'hora_salida' => null,
                'descanso_salida' => null,
                'descanso_entrada' => null,
                'libre' => null,
                'tipo' => 'flexible',
                'created_at' => '2024-04-02 10:17:38',
                'updated_at' => '2024-04-02 13:50:11',
                'total_horas' => null,
            ],
        ]);
    }
}