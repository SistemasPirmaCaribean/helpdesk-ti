<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TktTicketClaims extends Model
{
    //
    protected $table = 'tkt_ticket_claims';

    protected $fillable = [
        'idticket',
        'iduser',
        'type',
        'status',
        'reason',
        'active'
    ];

    public function ticket()
    {
        return $this->belongsTo(TktTicket::class, 'idticket');
    }

    public function user()
    {
        return $this->belongsTo(Usuario::class, 'iduser');
    }

    // Scope para obtener solo reclamaciones activas
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }   
}
