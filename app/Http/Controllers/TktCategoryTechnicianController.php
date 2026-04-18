<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TktCategoryTechnician;

class TktCategoryTechnicianController extends Controller
{
    //
    public function getTechnicians()
    {
        $technicians = TktCategoryTechnician::with('technician', 'category')->get();

        return response()->json($technicians);
    }
}
