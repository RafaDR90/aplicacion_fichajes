<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios';
    protected $fillable = ['nombre', 'hora_entrada', 'hora_salida', 'descanso_salida', 'descanso_entrada', 'libre', 'tipo'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'horario_emp', 'id_horario', 'id_empleado');
    }
}
