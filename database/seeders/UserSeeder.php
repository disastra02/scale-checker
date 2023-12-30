<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataUser = [
            [
                'name' => 'Checker A',
                'email' => 'checker_a',
                'is_mobile' => true,
                'id_jenis' => 2,
                'password' => Hash::make('12345678')
            ],
            [
                'name' => 'Checker B',
                'email' => 'checker_b',
                'is_mobile' => true,
                'id_jenis' => 2,
                'password' => Hash::make('12345678')
            ],
            [
                'name' => 'Checker C',
                'email' => 'checker_c',
                'is_mobile' => true,
                'id_jenis' => 2,
                'password' => Hash::make('12345678')
            ],
            [
                'name' => 'Security A',
                'email' => 'security_a',
                'is_mobile' => true,
                'id_jenis' => 3,
                'password' => Hash::make('12345678')
            ],
            [
                'name' => 'Security B',
                'email' => 'security_b',
                'is_mobile' => true,
                'id_jenis' => 3,
                'password' => Hash::make('12345678')
            ],
            [
                'name' => 'Security C',
                'email' => 'security_c',
                'is_mobile' => true,
                'id_jenis' => 3,
                'password' => Hash::make('12345678')
            ],
            [
                'name' => 'Admin Gudang',
                'email' => 'admin',
                'is_mobile' => false,
                'id_jenis' => 1,
                'password' => Hash::make('12345678')
            ]
        ];

        foreach($dataUser as $item) {
            User::create($item);
        }
    }
}
