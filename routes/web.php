<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\LotMapController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LeadIntegrationController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect('/login');
    }

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

Route::get('/login', fn () => auth()->check() ? redirect('/') : view('app', ['page' => 'login']));
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
Route::get('/api/me', [AuthController::class, 'profile'])->middleware('auth');
Route::patch('/api/profile', [AuthController::class, 'updateProfile'])->middleware('auth');

Route::prefix('api')->group(function () {
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::post('/sales', [SaleController::class, 'store']);
    Route::get('/developments/{developmentId}/lot-map', [LotMapController::class, 'show']);
    Route::post('/developments/{developmentId}/lot-map', [LotMapController::class, 'store']);
    Route::post('/developments/{developmentId}/lot-map/image', [LotMapController::class, 'upload']);
    Route::patch('/lot-map-areas/{areaId}', [LotMapController::class, 'updateArea']);
    Route::get('/materials', [MaterialController::class, 'index']);
    Route::post('/materials', [MaterialController::class, 'store']);
    Route::get('/reports/summary', [ReportController::class, 'summary']);
    Route::post('/leads', [LeadIntegrationController::class, 'store'])->middleware('throttle:60,1');
});

Route::get('/{any}', function () {
    if (! auth()->check()) {
        return redirect('/login');
    }

    return view('app', [
    'page' => 'dashboard',
    'user' => ['name' => 'Lucas Pascoal', 'role' => 'Administrador'],
    'stats' => [],
    ]);
})->where('any', '.*');
