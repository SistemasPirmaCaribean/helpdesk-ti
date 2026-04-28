<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TktTicketClaims;
use Illuminate\Support\Facades\DB;

class TktTicketClaimsController extends Controller
{
    //
    public function index()
    {
        //
        $claims = TktTicketClaims::active()->get();
        return response()->json($claims);

    }

    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'idticket' => 'required|integer|exists:tkt_tickets,id',
            'type' => 'required|string|in:claim,unclaim',
            'reason' => 'nullable|string|max:1000',
        ]);

        $iduser = auth()->id();
        $validated['iduser'] = $iduser;

        // Verificar si ya existe una reclamación activa del mismo tipo para el mismo ticket y usuario
        $existingClaim = TktTicketClaims::where('idticket', $validated['idticket'])
            ->where('iduser', $validated['iduser'])
            ->where('type', $validated['type'])
            ->where('active', true)
            ->first();

        if ($existingClaim) {
            return response()->json(['message' => 'Ya existe una reclamación activa de este tipo para este ticket y usuario'], 400);
        }

        $claim = TktTicketClaims::create($validated);

        return response()->json($claim, 201);
    }

    public function show($id)
    {
        //
        $claim = TktTicketClaims::findOrFail($id);
        return response()->json($claim);
    }

    public function destroy($id)
    {
        //
        $claim = TktTicketClaims::findOrFail($id);
        $claim->delete();
        return response()->json(['message' => 'Reclamación eliminada']);
    }

    public function showByTicket($idticket)
    {
        $claims = TktTicketClaims::with('user')->where('idticket', $idticket)->active()->get();
        return response()->json($claims);
    }

    public function approve($id, Request $request)
    {
        $validated = $request->validate([
            'idtechnician' => 'nullable|integer|exists:usuarios,id'
        ]);

        DB::transaction(function () use ($id, $validated) {

            $claim = TktTicketClaims::findOrFail($id);
            $claim->status = 'approved';
            $claim->save();

            $ticket = $claim->ticket;

            // Reasignar o asignar
            $ticket->idtechnician = $validated['idtechnician'] ?? $claim->iduser;
            $ticket->save();
        });

        return response()->json(['message' => 'Reclamación aprobada']);
    }

    public function reject($id, Request $request)
    {
        $validated = $request->validate([
            'idtechnician' => 'nullable|integer|exists:usuarios,id'
        ]);

        DB::transaction(function () use ($id, $validated) {

            $claim = TktTicketClaims::findOrFail($id);
            $claim->status = 'rejected';
            $claim->save();

        });

        return response()->json(['message' => 'Reclamación rechazada']);
    }

    public function approveUnclaim($id, Request $request)
    {
        $validated = $request->validate([
            'idtechnician' => 'nullable|integer|exists:usuarios,id'
        ]);

        DB::transaction(function () use ($id, $validated) {

            $claim = TktTicketClaims::findOrFail($id);
            $claim->status = 'approved';
            $claim->save();

            $ticket = $claim->ticket;

            // Reasignar o desasignar
            $ticket->idtechnician = $validated['idtechnician'] ?? null;
            $ticket->save();
        });

        return response()->json([
            'message' => 'Desasignación aprobada correctamente'
        ]);
    }

    public function rejectUnclaim($id)
    {


        DB::transaction(function () use ($id) {

            $claim = TktTicketClaims::findOrFail($id);
            $claim->status = 'rejected';
            $claim->save();

        });

        return response()->json(['message' => 'Desasignación rechazada']);
    }

    public function deactivateClaim($id)
    {
        $claim = TktTicketClaims::findOrFail($id);
        $claim->active = false;
        $claim->save();

        return response()->json(['message' => 'Reclamación desactivada']);
    }

}
