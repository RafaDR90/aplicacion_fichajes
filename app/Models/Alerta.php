<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    use HasFactory;

    protected $fillable = ['tipo', 'mensaje', 'datos', 'user_id', 'leido'];

    protected $casts = [
        'datos' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
