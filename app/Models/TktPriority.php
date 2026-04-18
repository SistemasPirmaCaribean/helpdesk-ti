<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TktPriority extends Model
{
    //
    protected $fillable = [
        'idcia',
        'name',
        'sla_hours',
        'color',
        'active',
    ];
}
