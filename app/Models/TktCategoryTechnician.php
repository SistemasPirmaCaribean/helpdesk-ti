<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TktCategoryTechnician extends Model
{
    //
    protected $table = 'tkt_category_technicians';
    protected $fillable = ['idcategory', 'idtechnician'];

    public function category()
    {
        return $this->belongsTo(TktCategory::class, 'idcategory');
    }

    public function technician()
    {
        return $this->belongsTo(Usuario::class, 'idtechnician');
    }

    
}
