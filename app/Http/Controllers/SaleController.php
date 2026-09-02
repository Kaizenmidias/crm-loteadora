<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\Reservation;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'broker_id' => ['nullable', 'exists:brokers,id'],
            'lot_id' => ['required', 'exists:lots,id'],
            'development_id' => ['required', 'exists:developments,id'],
            'reservation_id' => ['nullable', 'exists:reservations,id'],
            'value' => ['required', 'numeric', 'min:0'],
            'sold_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        return response()->json(DB::transaction(function () use ($data) {
            $lot = Lot::query()->whereKey($data['lot_id'])->lockForUpdate()->firstOrFail();
            if (!in_array($lot->status, ['available', 'reserved'], true)) {
                throw ValidationException::withMessages(['lot_id' => 'Este lote nao esta disponivel para venda.']);
            }
            $sale = Sale::create([...$data, 'sold_at' => $data['sold_at'] ?? today(), 'status' => 'confirmed']);
            $lot->update(['status' => 'sold']);
            if (!empty($data['reservation_id'])) {
                Reservation::query()->whereKey($data['reservation_id'])->update(['status' => 'converted']);
            }
            return $sale;
        }), 201);
    }
}
