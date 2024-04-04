<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Crear 100 usuarios con datos aleatorios
        User::factory()->count(100)->create();

        // Crear un usuario con el correo electrónico 'admin@admin.com'
        $admin = User::create([
            'name' => 'Admin',
            'apellidos' => 'Sanchez Rajoi',
            'email' => 'admin@admin.com',
            'requiere_ubicacion' => 0,
            'password' => bcrypt('admin'), // reemplaza 'password' con la contraseña que desees
        ]);

        // Asignar el rol de 'super-admin' al usuario
        $role = Role::where('role_name', 'super-admin')->first();
        $admin->roles()->attach($role);
    }
}
