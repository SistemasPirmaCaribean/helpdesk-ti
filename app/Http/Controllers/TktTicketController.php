<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TktTicket;
use App\Http\Controllers\TktLogController;
use App\Services\TicketLogService;

class TktTicketController extends Controller
{
    //
    public function index()
    {
        $user = auth()->user();
        $idcia = $user->idcia;
        $iduser = $user->id;
        $idPerfil = $user->idperfil;

        if ($idPerfil == 1) {

            $tickets = TktTicket::where('idcia', $idcia)
                ->with(['status', 'category', 'priority', 'user', 'technician', 'user.departamentoUsuario.departament'])
                ->withCount([
                    'comments as unread' => function ($query) use ($user) {
                        $query->where('iduser', '!=', $user->id)
                            ->whereNull('seen_at');
                    }
                ])
                ->get();

        } elseif ($idPerfil == 2) {

            $tickets = TktTicket::where('idcia', $idcia)->where('idtechnician', $iduser)
                ->with(['status', 'category', 'priority', 'user', 'technician', 'user.departamentoUsuario.departament'])
                ->withCount([
                    'comments as unread' => function ($query) use ($user) {
                        $query->where('iduser', '!=', $user->id)
                            ->whereNull('seen_at');
                    }
                ])
                ->get();

        } elseif ($idPerfil == 3) {

            $tickets = TktTicket::where('idcia', $idcia)->where('iduser', $iduser)
                ->with(['status', 'category', 'priority', 'user', 'technician', 'user.departamentoUsuario.departament'])
                ->withCount([
                    'comments as unread' => function ($query) use ($user) {
                        $query->where('iduser', '!=', $user->id)
                            ->whereNull('seen_at');
                    }
                ])
                ->get();

        } elseif ($idPerfil == 4) {
            $tickets = TktTicket::where('idcia', $idcia)
                ->with(['status', 'category', 'priority', 'user', 'technician', 'user.departamentoUsuario.departament'])

                ->whereHas('user.departamentoUsuario.departament', function ($query) use ($user) {
                    $query->where('iddepartament', $user->departamentoUsuario->iddepartament);
                })

                ->withCount([
                    'comments as unread' => function ($query) use ($user) {
                        $query->where('iduser', '!=', $user->id)
                            ->whereNull('seen_at');
                    }
                ])
                ->get();

        } elseif ($idPerfil == 5) {

            $tickets = TktTicket::where('idcia', $idcia)
                ->with(['status', 'category', 'priority', 'user', 'technician', 'user.departamentoUsuario.departament'])

                // Filtra por departamento
                ->whereHas('user.departamentoUsuario.departament', function ($query) use ($user) {
                    $query->where('iddepartament', $user->departamentoUsuario->iddepartament);
                })

                // ❌ Excluir usuarios con idperfil = 5
                ->whereHas('user', function ($query) {
                    $query->where('idperfil', '!=', 4);
                })

                ->withCount([
                    'comments as unread' => function ($query) use ($user) {
                        $query->where('iduser', '!=', $user->id)
                            ->whereNull('seen_at');
                    }
                ])
                ->get();
        }



        return response()->json($tickets);
    }

    public function show($id)
    {
        $idcia = auth()->user()->idcia;

        $ticket = TktTicket::where('id', $id)->where('idcia', $idcia)->firstOrFail();

        TicketLogService::log($ticket->id, 'Visualización de ticket');

        return response()->json($ticket);
    }

    public function destroy($id)
    {
        $idcia = auth()->user()->idcia;

        $ticket = TktTicket::where('id', $id)->where('idcia', $idcia)->firstOrFail();

        $ticket->delete();

        TicketLogService::log($ticket->id, 'Eliminación de ticket');

        return response()->json(['message' => 'Ticket eliminado']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'idcategory' => 'required|integer|exists:tkt_categories,id',
                'idpriority' => 'required|integer|exists:tkt_priorities,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
            ],
            [
                'idcategory.required' => 'La categoría es obligatoria',
                'idcategory.integer' => 'La categoría debe ser un número entero',
                'idcategory.exists' => 'La categoría seleccionada no existe',
                'idpriority.required' => 'La prioridad es obligatoria',
                'idpriority.integer' => 'La prioridad debe ser un número entero',
                'idpriority.exists' => 'La prioridad seleccionada no existe',
                'title.required' => 'El título es obligatorio',
                'title.string' => 'El título debe ser una cadena de texto',
                'title.max' => 'El título no puede exceder los 255 caracteres',
                'description.required' => 'La descripción es obligatoria',
                'description.string' => 'La descripción debe ser una cadena de texto',
            ]
        );

        $idcia = auth()->user()->idcia;
        $iduser = auth()->user()->id;

        $validated['idcia'] = $idcia;
        $validated['iduser'] = $iduser;

        if (!isset($validated['idtechnician'])) {
            $validated['idtechnician'] = null;
            $validated['idstatus'] = 1; // Asignar un estado inicial,
        } else {
            $validated['idstatus'] = 3; // Asignado
        }

        // Buscar el técnico asignado a la categoría y asignarlo automáticamente
        if (!isset($validated['idtechnician']) || $validated['idtechnician'] == null) {
            $categoryTechnician = \DB::table('tkt_category_technicians')
                ->where('idcategory', $validated['idcategory'])
                ->first();
            if ($categoryTechnician) {
                $validated['idtechnician'] = $categoryTechnician->idtechnician;
                $validated['idstatus'] = 3; // Asignado
            }
        }

        $validated['date_opened'] = now()->toDateTimeString();

        $validated['date_opened'] = now()->toDateTimeString();

        // Generar un número de referencia único
        $validated['reference_number'] = 'TKT-' . strtoupper(uniqid());

        $ticket = TktTicket::create($validated);

        // Registrar el log de creación del ticket

        TicketLogService::log($ticket->id, 'Creación de ticket');

        return response()->json($ticket, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate(
            [
                'idcategory' => 'sometimes|integer|exists:tkt_categories,id',
                'idpriority' => 'sometimes|integer|exists:tkt_priorities,id',
                'idtechnician' => 'nullable|integer|exists:usuarios,id',
                'title' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
            ],
            [
                'idcategory.sometimes' => 'La categoría es obligatoria',
                'idcategory.integer' => 'La categoría debe ser un número entero',
                'idcategory.exists' => 'La categoría seleccionada no existe',
                'idpriority.sometimes' => 'La prioridad es obligatoria',
                'idpriority.integer' => 'La prioridad debe ser un número entero',
                'idpriority.exists' => 'La prioridad seleccionada no existe',
                'idtechnician.integer' => 'El técnico asignado debe ser un número entero',
                'idtechnician.exists' => 'El técnico asignado no existe',
                'title.sometimes' => 'El título es obligatorio',
                'title.string' => 'El título debe ser una cadena de texto',
                'title.max' => 'El título no puede exceder los 255 caracteres',
                'description.sometimes' => 'La descripción es obligatoria',
                'description.string' => 'La descripción debe ser una cadena de texto',
            ]
        );

        $idcia = auth()->user()->idcia;

        if (isset($validated['idtechnician']) && $validated['idtechnician'] != null) {
            $validated['idstatus'] = 3; // Asignado
        }
 
        if (!isset($validated['idtechnician']) && $validated['idtechnician'] == null) {
            $validated['idstatus'] = 1; // Abierto
        }

        $ticket = TktTicket::where('id', $id)->where('idcia', $idcia)->firstOrFail();

        $originalData = $ticket->getOriginal();

        // Registrar los cambios en el log
        foreach ($validated as $field => $newValue) {
            $oldValue = $originalData[$field] ?? null;
            if ($oldValue != $newValue) {
                TicketLogService::log($ticket->id, 'Actualización de ticket', $field, $oldValue, $newValue);
            }
        }

        $ticket->update($validated);

        return response()->json($ticket);
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'idstatus' => 'required|integer|exists:tkt_statuses,id',
        ]);

        if ($validated['idstatus'] == 3 && !isset($request->idtechnician)) {
            return response()->json(['message' => 'El campo idtechnician es obligatorio cuando el estatus es Asignado'], 400);
        }



        $idcia = auth()->user()->idcia;

        $ticket = TktTicket::where('id', $id)->where('idcia', $idcia)->firstOrFail();

        $originalStatus = $ticket->status ? $ticket->status->name : null;

        if ($validated['idstatus'] == 7) {
            $validated['date_closed'] = now()->toDateTimeString();
        } else {
            $validated['date_closed'] = null;
        }
        $ticket->update(['idstatus' => $validated['idstatus'], 'date_closed' => $validated['date_closed']]);

        if ($validated['idstatus'] == 3) {
            $ticket->update(['idtechnician' => $request->idtechnician]);
        }

        $newStatus = $ticket->status ? $ticket->status->name : null;

        TicketLogService::log($ticket->id, 'Cambio de estatus', 'status', $originalStatus, $newStatus);

        return response()->json($ticket);
    }

}
