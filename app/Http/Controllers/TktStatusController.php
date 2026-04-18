<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TktStatus;

class TktStatusController extends Controller
{
    //
    public function index()
    {
        $statuses = TktStatus::all();

        return response()->json($statuses);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
        ], [
            'name.required' => 'El nombre del estado es obligatorio',
            'name.string' => 'El nombre del estado debe ser una cadena de texto',
            'name.max' => 'El nombre del estado no puede exceder los 255 caracteres',
            'color.string' => 'El color del estado debe ser una cadena de texto',
            'color.max' => 'El color del estado no puede exceder los 7 caracteres',
        ]);

        $idcia = auth()->user()->idcia;

        $status = TktStatus::create([
            'name' => $request->name,
            'color' => $request->color,
            'idcia' => $idcia,
        ]);

        return response()->json($status, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
        ], [
            'name.required' => 'El nombre del estado es obligatorio',
            'name.string' => 'El nombre del estado debe ser una cadena de texto',
            'name.max' => 'El nombre del estado no puede exceder los 255 caracteres',
            'color.string' => 'El color del estado debe ser una cadena de texto',
            'color.max' => 'El color del estado no puede exceder los 7 caracteres',
        ]);

        $idcia = auth()->user()->idcia;

        $status = TktStatus::where('id', $id)->where('idcia', $idcia)->firstOrFail();

        $status->update([
            'name' => $request->name,
            'color' => $request->color,
        ]);

        return response()->json($status);
    }

    public function show($id)
    {
        $idcia = auth()->user()->idcia;

        $status = TktStatus::where('id', $id)->where('idcia', $idcia)->firstOrFail();

        return response()->json($status);
    }

    public function destroy($id)
    {
        $idcia = auth()->user()->idcia;

        $status = TktStatus::where('id', $id)->where('idcia', $idcia)->firstOrFail();

        $status->delete();

        return response()->json(['message' => 'Estado eliminado']);
    }
}
