<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TktTicketComment;

class TktTicketCommentController extends Controller
{
    //
    public function index()
    {

        $comments = TktTicketComment::all();

        return response()->json($comments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'idticket' => 'required|integer|exists:tkt_tickets,id',
            'comment' => 'required|string|max:1000',
        ], );

        $validated['iduser'] = auth()->user()->id;

        $comment = TktTicketComment::create($validated);

        return response()->json($comment, 201);
    }

    public function show($id)
    {

        $comment = TktTicketComment::where('id', $id)->firstOrFail();

        return response()->json($comment);
    }

    public function destroy($id)
    {

        $comment = TktTicketComment::where('id', $id)->firstOrFail();

        $comment->delete();

        return response()->json(['message' => 'Comentario eliminado']);
    }

    public function showByTicket($idticket)
    {
        // Marcar como vistos los mensajes de otros usuarios
        TktTicketComment::where('idticket', $idticket)
            ->where('iduser', '!=', auth()->user()->id)
            ->update([
                'seen' => 1,
                'seen_at' => now()
            ]);

        // Obtener los comentarios
        $comments = TktTicketComment::with('user')
            ->where('idticket', $idticket)
            ->orderBy('created_at')
            ->get();

        return response()->json($comments);
    }

}
