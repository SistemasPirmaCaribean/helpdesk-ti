<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TktPriority;

class TktPriorityController extends Controller
{
    //
    public function index()
    {
        $idcia = auth()->user()->idcia;

        $priorities = TktPriority::where('idcia', $idcia)->get();

        return response()->json($priorities);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
            'sla_hours' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'El nombre de la prioridad es obligatorio',
            'name.string' => 'El nombre de la prioridad debe ser una cadena de texto',
            'name.max' => 'El nombre de la prioridad no puede exceder los 255 caracteres',
            'color.string' => 'El color de la prioridad debe ser una cadena de texto',
            'color.max' => 'El color de la prioridad no puede exceder los 7 caracteres',
            'sla_hours.integer' => 'Las horas de SLA deben ser un número entero',
            'sla_hours.min' => 'Las horas de SLA no pueden ser negativas',
        ]);

        $idcia = auth()->user()->idcia;

        $priority = TktPriority::create([
            'name' => $request->name,
            'color' => $request->color,
            'sla_hours' => $request->sla_hours,
            'idcia' => $idcia,
        ]);

        return response()->json($priority, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
            'sla_hours' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'El nombre de la prioridad es obligatorio',
            'name.string' => 'El nombre de la prioridad debe ser una cadena de texto',
            'name.max' => 'El nombre de la prioridad no puede exceder los 255 caracteres',
            'color.string' => 'El color de la prioridad debe ser una cadena de texto',
            'color.max' => 'El color de la prioridad no puede exceder los 7 caracteres',
            'sla_hours.integer' => 'Las horas de SLA deben ser un número entero',
            'sla_hours.min' => 'Las horas de SLA no pueden ser negativas',
        ]);

        $idcia = auth()->user()->idcia;

        $priority = TktPriority::where('id', $id)->where('idcia', $idcia)->firstOrFail();

        $priority->update([
            'name' => $request->name,
            'color' => $request->color,
            'sla_hours' => $request->sla_hours,
        ]);

        return response()->json($priority);
    }

    public function show($id)
    {
        $idcia = auth()->user()->idcia;

        $priority = TktPriority::where('id', $id)->where('idcia', $idcia)->firstOrFail();

        return response()->json($priority);
    }

    public function destroy($id)
    {
        $idcia = auth()->user()->idcia;

        $priority = TktPriority::where('id', $id)->where('idcia', $idcia)->firstOrFail();

        $priority->delete();

        return response()->json(['message' => 'Prioridad eliminada']);
    }
}
