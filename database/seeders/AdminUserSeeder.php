<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'agenciakaizendesign@gmail.com'],
            [
                'name' => 'Lucas',
                'password' => Hash::make('Kaizen@@2026'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
        );
    }
}
