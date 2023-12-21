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
                'email' => 'checkera@gmail.com',
                'is_mobile' => true,
                'id_jenis' => 2,
                'password' => Hash::make('12345678')
            ],
            [
                'name' => 'Checker B',
                'email' => 'checkerb@gmail.com',
                'is_mobile' => true,
                'id_jenis' => 2,
                'password' => Hash::make('12345678')
            ],
            [
                'name' => 'Admin Gudang',
                'email' => 'admin@gmail.com',
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
