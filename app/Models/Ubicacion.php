<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    use HasFactory;
    protected $table = 'ubicacion';
    protected $fillable = ['pais', 'ciudad', 'latitud', 'longitud', 'cp'];

    public function user()
    {
        return $this->belongsToMany(User::class, 'ubicacion_permitida', 'id_user', 'id_ubicacion');
    }
}
