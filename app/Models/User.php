<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\RoleEmp;
use App\Models\DiasVacaciones;
use Illuminate\Database\Eloquent\SoftDeletes;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'apellidos',
        'email',
        'telefono',
        'direccion',
        'password',
        'requiere_ubicacion',
        'image_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_emp')->withTimestamps();
    }

    public function horarios()
    {
        return $this->belongsToMany(Horario::class, 'horario_emp','id_empleado', 'id_horario')->withPivot('dias')->withTimestamps();
    }

    public function ubicacion()
    {
        return $this->belongsToMany(Ubicacion::class, 'ubicacion_permitida')->withTimestamps();
    }

    public function diasVacaciones()
    {
        return $this->hasOne(DiasVacaciones::class);
    }

    public function vacaciones()
    {
        return $this->hasMany(Vacaciones::class);
    }

    public function fichajes()
    {
        return $this->hasMany(Fichaje::class);
    }
}