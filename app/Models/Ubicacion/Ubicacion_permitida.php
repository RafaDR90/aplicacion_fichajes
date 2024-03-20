<?php

namespace App\Models\Ubicacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion_permitida extends Model
{
    use HasFactory;
    protected $table = 'ubicacion_permitida';
    protected $fillable = ['user_id', 'ubicacion_id'];

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class);
    }
}
