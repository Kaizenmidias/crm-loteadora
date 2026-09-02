<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Lead;
use App\Models\Lot;
use App\Models\Reservation;
use App\Models\Sale;

class ReportController extends Controller
{
    public function summary()
    {
        return response()->json([
            'leads' => Lead::count(),
            'clients' => Client::count(),
            'reservations' => Reservation::count(),
            'sales' => Sale::count(),
            'sold_value' => (float) Sale::sum('value'),
            'lots' => [
                'available' => Lot::where('status', 'available')->count(),
                'reserved' => Lot::where('status', 'reserved')->count(),
                'sold' => Lot::where('status', 'sold')->count(),
                'blocked' => Lot::where('status', 'blocked')->count(),
            ],
        ]);
    }
}
