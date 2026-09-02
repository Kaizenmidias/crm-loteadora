<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(User::query()->select('id', 'name', 'email', 'role', 'permissions', 'created_at')->latest()->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:user'],
            'permissions' => ['array'],
            'permissions.*' => ['string', 'max:60'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
            'permissions' => array_values(array_unique($data['permissions'] ?? [])),
        ]);

        return response()->json($user->only(['id', 'name', 'email', 'role', 'permissions']), 201);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'in:user'],
            'permissions' => ['array'],
            'permissions.*' => ['string', 'max:60'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = 'user';
        $user->permissions = array_values(array_unique($data['permissions'] ?? []));
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        return response()->json($user->only(['id', 'name', 'email', 'role', 'permissions']));
    }
}
