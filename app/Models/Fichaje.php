<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fichaje extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'ubicacion_id', 'tipo', 'observaciones'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function EsDeHoy()
    {
        //creo variable fechaActual con la fecha de mañana
        
        return $this->created_at->format('Y-m-d') == now()->format('Y-m-d');
    }
}
