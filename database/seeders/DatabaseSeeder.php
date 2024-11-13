<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'talayen',
            'balance' => 1000.0,
            'email' => 'talayen@gmail.com',
            'password' => Hash::make('123'),
        ]);

        $this->call([
            UserSeeder::class,
        ]);
    }
}
