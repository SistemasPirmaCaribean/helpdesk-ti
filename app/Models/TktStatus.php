<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TktStatus extends Model
{
    //
    protected $fillable = [
        'idcia',
        'name',
        'color',
        'active',
    ];
}
