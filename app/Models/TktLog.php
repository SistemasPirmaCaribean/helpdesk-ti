<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TktLog extends Model
{
    //
    protected $fillable = [
        'idticket',
        'iduser',
        'action',
        'field',
        'old_value',
        'new_value',
    ];
}
