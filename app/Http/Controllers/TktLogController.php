<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TktLog;

class TktLogController extends Controller
{
    //
    public function index()
    {

        $logs = TktLog::all();

        return response()->json($logs);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'idticket' => 'required|integer|exists:tkt_tickets,id',
            'iduser' => 'required|integer|exists:usuarios,id',
            'action' => 'required|string|max:255',
            'field' => 'required|string|max:100',
            'old_value' => 'nullable|string|max:255',
            'new_value' => 'nullable|string|max:255',
        ], );

        $log = TktLog::create($validated);

        return response()->json($log, 201);
    }

    public function show($id)
    {
        $idcia = auth()->user()->idcia;

        $log = TktLog::where('id', $id)->firstOrFail();

        return response()->json($log);
    }

    public function destroy($id)
    {
        $idcia = auth()->user()->idcia;

        $log = TktLog::where('id', $id)->firstOrFail();

        $log->delete();

        return response()->json(['message' => 'Log eliminado']);
    }
}
