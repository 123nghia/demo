<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@hovi.local'],
            [
                'name' => 'HOVI Admin',
                'password' => Hash::make('admin12345'),
            ]
        );
    }
}
