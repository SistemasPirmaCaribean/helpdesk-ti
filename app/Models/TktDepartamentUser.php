<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TktDepartamentUser extends Model
{
    //
    protected $fillable = [
        'iduser',
        'iddepartament',
    ];

    public function user()
    {
        return $this->belongsTo(Usuario::class, 'iduser');
    }

    public function departament()
    {
        return $this->belongsTo(TktDepartament::class, 'iddepartament');
    }
}
