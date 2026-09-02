<?php

namespace App\Services;

use App\Models\Lot;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReservationService
{
    public function create(array $data): Reservation
    {
        return DB::transaction(function () use ($data) {
            $lot = Lot::query()->whereKey($data['lot_id'])->lockForUpdate()->firstOrFail();
            if ($lot->status !== 'available') {
                throw ValidationException::withMessages(['lot_id' => 'Este lote nao esta mais disponivel para reserva.']);
            }
            $reservation = Reservation::create([...$data, 'development_id' => $data['development_id'] ?? $lot->development_id, 'reserved_at' => now(), 'expires_at' => $data['expires_at'] ?? now()->addHours(24), 'status' => 'pending']);
            $lot->update(['status' => 'reserved']);
            return $reservation;
        });
    }
}
