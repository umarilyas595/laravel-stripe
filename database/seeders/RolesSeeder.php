<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::factory()->create([
            'name' => 'Admin',
            'description' => 'This is the admin role',
        ]);

        Role::factory()->create([
            'name' => 'B2C Customer',
            'description' => 'This is the B2C Customer role',
        ]);

        Role::factory()->create([
            'name' => 'B2B Customer',
            'description' => 'This is the B2B Customer role',
        ]);
    }
}