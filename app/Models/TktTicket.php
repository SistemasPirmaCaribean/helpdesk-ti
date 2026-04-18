<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TktTicket extends Model
{
    //
    protected $fillable = [
        'idcia',
        'idstatus',
        'idcategory',
        'idpriority',
        'iduser',
        'idtechnician',
        'reference_number',
        'title',
        'description',
        'date_opened',
        'date_closed',
        'active',
    ];

    public function status()
    {
        return $this->belongsTo(TktStatus::class, 'idstatus');
    }

    public function category()
    {
        return $this->belongsTo(TktCategory::class, 'idcategory');
    }

    public function priority()
    {
        return $this->belongsTo(TktPriority::class, 'idpriority');
    }

    public function user()
    {
        return $this->belongsTo(Usuario::class, 'iduser');
    }

    public function technician()
    {
        return $this->belongsTo(Usuario::class, 'idtechnician');
    }

    public function comments()
    {
        return $this->hasMany(TktTicketComment::class, 'idticket');
    }
}
