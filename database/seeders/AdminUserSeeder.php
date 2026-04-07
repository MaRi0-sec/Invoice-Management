<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;


class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Mario',
            'email' => 'mario@gmail.com',
            'password' => bcrypt('12345678'),
            'status' => '1',
        ]);

        $user->assignRole('admin');
    }
}
