<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Horario extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'horarios';
    protected $fillable = ['nombre', 'hora_entrada', 'hora_salida', 'descanso_salida', 'descanso_entrada', 'libre', 'tipo', 'total_horas'];

    

    public function users()
    {
        return $this->belongsToMany(User::class, 'horario_emp', 'id_horario', 'id_empleado');
    }
}
