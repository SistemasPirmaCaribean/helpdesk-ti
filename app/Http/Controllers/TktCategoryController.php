<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TktCategory;

class TktCategoryController extends Controller
{
    //
    public function index()
    {
        $idcia = auth()->user()->idcia;

        $categories = TktCategory::where('idcia', $idcia)->get();

        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'El nombre de la categoría es obligatorio',
            'name.string' => 'El nombre de la categoría debe ser una cadena de texto',
            'name.max' => 'El nombre de la categoría no puede exceder los 255 caracteres',
        ]);

        $idcia = auth()->user()->idcia;

        $category = TktCategory::create([
            'name' => $request->name,
            'idcia' => $idcia,
        ]);

        return response()->json($category, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'El nombre de la categoría es obligatorio',
            'name.string' => 'El nombre de la categoría debe ser una cadena de texto',
            'name.max' => 'El nombre de la categoría no puede exceder los 255 caracteres',
        ]);

        $idcia = auth()->user()->idcia;

        $category = TktCategory::where('id', $id)->where('idcia', $idcia)->firstOrFail();

        $category->update([
            'name' => $request->name,
        ]);

        return response()->json($category);
    }

    public function destroy($id)
    {
        $idcia = auth()->user()->idcia;

        $category = TktCategory::where('id', $id)->where('idcia', $idcia)->firstOrFail();

        $category->delete();

        return response()->json(['message' => 'Categoría eliminada']);
    }

    public function show($id)
    {
        $idcia = auth()->user()->idcia;

        $category = TktCategory::where('id', $id)->where('idcia', $idcia)->firstOrFail();

        return response()->json($category);
    }
}
