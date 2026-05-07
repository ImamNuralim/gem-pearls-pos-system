<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat roles
        $roles = ['owner', 'admin', 'kasir', 'security'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Buat user owner default
        $owner = User::firstOrCreate(
            ['email' => 'owner@gempearlslombok.com'],
            [
                'name' => 'Owner Gem Pearls',
                'password' => Hash::make('gempearlsowner123'),
            ]
        );
        $owner->assignRole('owner');

        // Buat user admin default
        $admin = User::firstOrCreate(
            ['email' => 'admin@gempearlslombok.com'],
            [
                'name' => 'Admin Gem Pearls',
                'password' => Hash::make('gempearlsadmin123'),
            ]
        );
        $admin->assignRole('admin');

        // Buat user kasir default
        $kasir = User::firstOrCreate(
            ['email' => 'kasir@gempearlslombok.com'],
            [
                'name' => 'Kasir Gem Pearls',
                'password' => Hash::make('gempearlskasir123'),
            ]
        );
        $kasir->assignRole('kasir');
    }
}
