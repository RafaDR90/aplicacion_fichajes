<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;

class RoleEmp extends Model
{
    use HasFactory;

    protected $table = 'role_emp';
    protected $fillable = ['role_id', 'user_id'];
    
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

}
