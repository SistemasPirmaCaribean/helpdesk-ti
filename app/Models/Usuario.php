<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Perfil;
use App\Models\TktDepartamentUser;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'nombre',
        'usuario',
        'hash',
        'activo',
    ];

    protected $hidden = [
        'hash',
        'created_at',
        'updated_at',
    ];

    public function getAuthPassword()
    {
        return $this->hash;
    }

    protected $casts = [
        'hash' => 'hashed',
    ];

    public function perfil()
    {
        return $this->hasOne(Perfil::class, 'idPerfil');
    }

    public function departamentoUsuario()
    {
        return $this->hasOne(TktDepartamentUser::class, 'iduser');
    }
}
