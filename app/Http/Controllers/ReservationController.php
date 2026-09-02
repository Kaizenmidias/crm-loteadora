<?php

namespace App\Http\Controllers;

use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function store(Request $request, ReservationService $service)
    {
        $data = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'broker_id' => ['nullable', 'exists:brokers,id'],
            'lot_id' => ['required', 'exists:lots,id'],
            'development_id' => ['nullable', 'exists:developments,id'],
            'expires_at' => ['nullable', 'date', 'after:now'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        return response()->json($service->create($data), 201);
    }
}
