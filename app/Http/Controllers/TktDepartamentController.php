<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TktDepartament;

class TktDepartamentController extends Controller
{
    //
    public function index()
    {
        $idcia = auth()->user()->idcia;

        $departaments = TktDepartament::where('idcia', $idcia)->get();

        return response()->json($departaments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'El nombre del departamento es obligatorio',
            'name.string' => 'El nombre del departamento debe ser una cadena de texto',
            'name.max' => 'El nombre del departamento no puede exceder los 255 caracteres',
        ]);

        $idcia = auth()->user()->idcia;

        $departament = TktDepartament::create([
            'name' => $request->name,
            'idcia' => $idcia,
        ]);

        return response()->json($departament, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'El nombre del departamento es obligatorio',
            'name.string' => 'El nombre del departamento debe ser una cadena de texto',
            'name.max' => 'El nombre del departamento no puede exceder los 255 caracteres',
        ]);

        $idcia = auth()->user()->idcia;

        $departament = TktDepartament::where('id', $id)->where('idcia', $idcia)->firstOrFail();

        $departament->update([
            'name' => $request->name,
        ]);

        return response()->json($departament);
    }

    public function destroy($id)
    {
        $idcia = auth()->user()->idcia;

        $departament = TktDepartament::where('id', $id)->where('idcia', $idcia)->firstOrFail();

        $departament->delete();

        return response()->json(['message' => 'Departamento eliminado']);
    }

    public function show($id)
    {
        $idcia = auth()->user()->idcia;

        $departament = TktDepartament::where('id', $id)->where('idcia', $idcia)->firstOrFail();

        return response()->json($departament);
    }
}
