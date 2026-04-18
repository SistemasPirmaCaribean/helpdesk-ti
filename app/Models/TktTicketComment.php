<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TktTicketComment extends Model
{
    //
    protected $fillable = [
        'idticket',
        'iduser',
        'comment',
    ];

    public function ticket()
    {
        return $this->belongsTo(TktTicket::class, 'idticket');
    }

    public function user()
    {
        return $this->belongsTo(Usuario::class, 'iduser');
    }
}
