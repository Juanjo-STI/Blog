<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
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
        User::create([
            'full_name' => 'Juan José Vasquez',
            'email' => 'vasquezjuan_jose@hotmail.com',
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'full_name' => 'Patán',
            'email' => 'paty@hotmail.com',
            'password' => Hash::make('paty'),
        ]);

        User::create([
            'full_name' => 'Enano',
            'email' => 'enano@hotmail.com',
            'password' => Hash::make('enano'),
        ]);

        User::factory(10)->create();
    }
}
