<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'role_id' => 1,
            'name' => 'admin',
            'email' => 'chere@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Shine@123'),
            'remember_token' => Str::random(10),
            'phone_number' => rand(1234567890, 9999999999),
            'status' => '1'
        ];

        User::create($data);
        
        // $count = (int) $this->command->ask('Number of users to be created?', 10);
        // User::factory()->count($count)->create();
        // $this->command->info($count . ' users have been created');
    }
}
