<?php

namespace App\Http\Controllers;

use App\Models\Development;
use App\Models\LotMap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LotMapController extends Controller
{
    public function show(int $developmentId)
    {
        $development = Development::find($developmentId);
        if (!$development) {
            return response()->json(['map' => null, 'areas' => []]);
        }
        $map = $development->maps()->where('is_active', true)->with('areas')->latest()->first();

        return response()->json([
            'map' => $map,
            'areas' => $map?->areas ?? [],
        ]);
    }

    public function store(Request $request, int $developmentId)
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:150'],
            'file_path' => ['nullable', 'string', 'max:500'],
            'file_type' => ['nullable', 'string', 'max:20'],
            'areas' => ['required', 'array'],
            'areas.*.type' => ['required', 'in:quadra,lote'],
            'areas.*.label' => ['required', 'string', 'max:100'],
            'areas.*.x' => ['required', 'numeric', 'between:0,100'],
            'areas.*.y' => ['required', 'numeric', 'between:0,100'],
            'areas.*.size' => ['nullable', 'numeric', 'between:24,100'],
            'areas.*.block_label' => ['nullable', 'string', 'max:100'],
            'areas.*.lot_id' => ['nullable', 'integer'],
            'areas.*.development_label' => ['nullable', 'string', 'max:150'],
            'areas.*.address' => ['nullable', 'string', 'max:255'],
            'areas.*.value' => ['nullable', 'numeric'],
            'areas.*.area' => ['nullable', 'numeric'],
            'areas.*.price_per_m2' => ['nullable', 'numeric'],
            'areas.*.status' => ['nullable', 'string', 'max:30'],
        ]);

        $development = Development::find($developmentId);
        if (!$development) {
            $development = Development::create([
                'name' => $data['name'] ?? 'Novo loteamento',
                'slug' => 'loteamento-'.$developmentId.'-'.uniqid(),
                'type' => 'loteamento',
                'status' => 'active',
            ]);
        }

        $map = DB::transaction(function () use ($data, $development) {
            $map = LotMap::updateOrCreate(
                ['development_id' => $development->id, 'is_active' => true],
                [
                    'name' => $data['name'] ?? $development->name,
                    'file_path' => $data['file_path'] ?? null,
                    'file_type' => $data['file_type'] ?? 'webp',
                ]
            );

            $map->areas()->delete();
            foreach ($data['areas'] as $area) {
                $map->areas()->create([
                    'type' => $area['type'],
                    'label' => $area['label'],
                    'identifier' => $area['type'].'-'.$area['label'],
                    'lot_id' => $area['type'] === 'lote' ? ($area['lot_id'] ?? null) : null,
                    'x' => $area['x'],
                    'y' => $area['y'],
                    'size' => $area['size'] ?? ($area['type'] === 'quadra' ? 42 : 30),
                    'block_label' => $area['block_label'] ?? null,
                    'development_label' => $area['development_label'] ?? $development->name,
                    'address' => $area['address'] ?? null,
                    'value' => $area['value'] ?? null,
                    'area' => $area['area'] ?? null,
                    'price_per_m2' => $area['price_per_m2'] ?? null,
                    'status' => $area['status'] ?? null,
                    'coordinates' => json_encode(['x' => $area['x'], 'y' => $area['y']]),
                ]);
            }

            return $map->load('areas');
        });

        return response()->json(['map' => $map, 'areas' => $map->areas]);
    }

    public function upload(Request $request, int $developmentId)
    {
        $request->validate(['image' => ['required', 'image', 'max:10240']]);
        $path = $request->file('image')->store('lot-maps', 'public');

        return response()->json(['path' => '/storage/'.$path]);
    }

    public function updateArea(Request $request, int $areaId)
    {
        $area = \App\Models\LotMapArea::findOrFail($areaId);
        $data = $request->validate([
            'development_label' => ['nullable', 'string', 'max:150'],
            'address' => ['nullable', 'string', 'max:255'],
            'value' => ['required', 'numeric', 'min:0'],
            'area' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'max:30'],
        ]);
        $data['development_label'] = $data['development_label'] ?? $area->development_label ?? $area->map?->development?->name ?? 'Loteamento';
        $data['address'] = $data['address'] ?? $area->address;
        $data['price_per_m2'] = $data['area'] > 0 ? round($data['value'] / $data['area'], 2) : 0;
        $area->update($data);

        return response()->json(['area' => $area->fresh()]);
    }
}
