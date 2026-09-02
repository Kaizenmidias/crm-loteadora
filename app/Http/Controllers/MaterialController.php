<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        return response()->json(Material::with('development')->latest()->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'type' => ['required', 'string', 'max:40'],
            'development_id' => ['nullable', 'exists:developments,id'],
            'file' => ['required', 'file', 'max:20480', 'mimes:pdf,png,jpg,jpeg,webp,xlsx,xls,doc,docx'],
        ]);
        $file = $request->file('file');
        $path = $file->store('materials', 'public');
        $material = Material::create([
            'development_id' => $data['development_id'] ?? null,
            'created_by' => $request->user()?->id,
            'name' => $data['name'],
            'type' => $data['type'],
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);
        return response()->json($material->load('development'), 201);
    }
}
