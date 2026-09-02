<?php

namespace App\Console\Commands;

use App\Models\Lot;
use App\Models\Reservation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpireReservations extends Command
{
    protected $signature = 'reservations:expire';
    protected $description = 'Libera lotes de reservas vencidas';

    public function handle(): int
    {
        Reservation::query()->where('status', 'pending')->whereNotNull('expires_at')->where('expires_at', '<=', now())->each(function (Reservation $reservation) {
            DB::transaction(function () use ($reservation) {
                $reservation->update(['status' => 'expired']);
                Lot::query()->whereKey($reservation->lot_id)->where('status', 'reserved')->update(['status' => 'available']);
            });
        });
        $this->info('Reservas vencidas processadas.');
        return self::SUCCESS;
    }
}
