<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\LotMapController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LeadIntegrationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect('/login');
    }

    return view('app', [
        'page' => 'dashboard',
        'user' => auth()->user()->only(['id', 'name', 'email', 'role', 'permissions']),
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
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
Route::get('/api/me', [AuthController::class, 'profile'])->middleware('auth');
Route::patch('/api/profile', [AuthController::class, 'updateProfile'])->middleware('auth');

Route::prefix('api')->middleware('auth')->group(function () {
    Route::get('/leads', [LeadIntegrationController::class, 'index'])->middleware('permission:leads');
    Route::patch('/leads/{lead}', [LeadIntegrationController::class, 'update'])->middleware('permission:leads');
    Route::post('/reservations', [ReservationController::class, 'store'])->middleware('permission:reservations');
    Route::post('/sales', [SaleController::class, 'store'])->middleware('permission:sales');
    Route::get('/developments/{developmentId}/lot-map', [LotMapController::class, 'show'])->middleware('permission:lot-map');
    Route::post('/developments/{developmentId}/lot-map', [LotMapController::class, 'store'])->middleware('permission:lot-map');
    Route::post('/developments/{developmentId}/lot-map/image', [LotMapController::class, 'upload'])->middleware('permission:lot-map');
    Route::patch('/lot-map-areas/{areaId}', [LotMapController::class, 'updateArea'])->middleware('permission:lots');
    Route::get('/materials', [MaterialController::class, 'index'])->middleware('permission:materials');
    Route::post('/materials', [MaterialController::class, 'store'])->middleware('permission:materials');
    Route::get('/reports/summary', [ReportController::class, 'summary'])->middleware('permission:reports');
    Route::get('/users', [UserController::class, 'index'])->middleware('admin');
    Route::post('/users', [UserController::class, 'store'])->middleware('admin');
    Route::patch('/users/{user}', [UserController::class, 'update'])->middleware('admin');
});

Route::post('/api/leads', [LeadIntegrationController::class, 'store'])->middleware('throttle:60,1');

Route::get('/{any}', function () {
    if (! auth()->check()) {
        return redirect('/login');
    }

    $permissions = [
        'dashboard' => 'dashboard', 'loteamentos' => 'lot-developments', 'condominios' => 'condos',
        'imoveis' => 'properties', 'lotes' => 'lots', 'mapa-de-lotes' => 'lot-map', 'leads' => 'leads',
        'pipeline' => 'pipeline', 'clientes' => 'clients', 'corretores' => 'brokers', 'atividades' => 'activities',
        'reservas' => 'reservations', 'vendas' => 'sales', 'tarefas' => 'tasks', 'relatorios' => 'reports', 'materiais' => 'materials', 'usuarios' => 'users',
    ];
    $permission = $permissions[$any] ?? 'dashboard';
    abort_unless(auth()->user()->canAccess($permission), 403);

    return view('app', [
        'page' => $permission,
        'user' => auth()->user()->only(['id', 'name', 'email', 'role', 'permissions']),
        'stats' => [],
    ]);
})->where('any', '.*');
