<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiasVacaciones extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'dias_disponibles'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
