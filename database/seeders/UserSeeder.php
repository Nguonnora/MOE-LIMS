<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $roles = ['admin', 'technician', 'approver', 'receptionist', 'viewer'];
        $names = ['Admin', 'Technician', 'Approver', 'Receptionist', 'Viewer'];
        foreach ($roles as $i => $role) {
            User::create([
                'name' => $names[$i] . ' User',
                'email' => $role . '@example.com',
                'password' => Hash::make('password'),
                'role' => $role,
            ]);
        }
    }
}