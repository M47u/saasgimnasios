<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SaasUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('saas_users')->insert([
            'nombre'     => 'Admin',
            'email'      => 'admin@saas.com',
            'password'   => Hash::make('password'),
            'rol'        => 'super_admin',
            'activo'     => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}