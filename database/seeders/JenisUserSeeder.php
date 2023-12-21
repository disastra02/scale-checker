<?php

namespace Database\Seeders;

use App\Models\Web\JenisUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataUser = [
            [
                'name' => 'Admin'
            ],
            [
                'name' => 'Checker'
            ]
        ];

        foreach($dataUser as $item) {
            JenisUser::create($item);
        }
    }
}
