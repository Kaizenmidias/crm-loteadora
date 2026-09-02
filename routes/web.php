<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SaleController;

Route::get('/', function () {
    return view('app', [
        'page' => 'dashboard',
        'user' => ['name' => 'Lucas Pascoal', 'role' => 'Administrador'],
        'stats' => [
            ['label' => 'Vendas no mês', 'value' => 'R$ 1.248.500', 'change' => '+12,5%', 'tone' => 'red'],
            ['label' => 'Novos leads', 'value' => '284', 'change' => '+8,2%', 'tone' => 'blue'],
            ['label' => 'Lotes disponíveis', 'value' => '148', 'change' => '12 novos', 'tone' => 'green'],
            ['label' => 'Conversão', 'value' => '18,6%', 'change' => '+2,4%', 'tone' => 'amber'],
        ],
    ]);
});

Route::get('/login', fn () => view('app', ['page' => 'login']));

Route::prefix('api')->group(function () {
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::post('/sales', [SaleController::class, 'store']);
});
