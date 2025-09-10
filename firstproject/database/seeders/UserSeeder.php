<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'User1',
            'email' => 'user1@gmail.com',
            'password' => bcrypt('123'),
        ]);

        User::create([
            'name' => 'User2',
            'email' => 'user2@gmail.com',
            'password' => bcrypt('123'),
        ]);

        User::create([
            'name' => 'User3',
            'email' => 'user3@gmail.com',
            'password' => bcrypt('123'),
        ]);
    }
}
