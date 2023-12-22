<?php

namespace Database\Seeders;

use App\Models\Master\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataCustomer = [
            [
                'name' => 'Atma Sitompul',
                'address' => 'Jl. Cihapit, Cihapit, Kec. Bandung Wetan, Kota Bandung'
            ], 
            [
                'name' => 'Daliono Wibowo',
                'address' => 'Kebun Jayanti, Kec. Kiaracondong, Kota Bandung'
            ],
            [
                'name' => 'Patricia Rahmawati',
                'address' => 'Jl. Gegerkalong Tengah No.35 A, Gegerkalong, Kec. Sukasari, Kota Bandung'
            ],
            [
                'name' => 'Dagel Mahendra',
                'address' => 'Jl. Leuwi Panjang No.8, Situsaeur, Bojongloa Kidul, Kota Bandung'
            ],
            [
                'name' => 'Puput Laksmiwati',
                'address' => 'Jl. A.H. Nasution No.25B, Pasirwangi, Kec. Ujung Berung, Kota Bandungt'
            ],
            [
                'name' => 'Wani Handayani',
                'address' => 'Jl. Kesatriaan No.13, Arjuna, Kec. Cicendo, Kota Bandung'
            ],
            [
                'name' => 'Tami Zulaika',
                'address' => 'Jl. Puyuh, Sadang Serang, Kecamatan Coblong, Kota Bandung'
            ],
            [
                'name' => 'Adhiarja Wibisono',
                'address' => 'Jl. Peta, Suka Asih, Kec. Bojongloa Kaler, Kota Bandung'
            ],
            [
                'name' => 'Hafshah Kusmawati',
                'address' => 'Jl. Moh. Toha No.77, Cigereleng, Kec. Regol, Kota Bandung'
            ],
            [
                'name' => 'Zahra Andriani',
                'address' => 'Jl. Cijerah Girang, Kecamatan Bandung Kulon, Kota Bandung'
            ]
        ];

        foreach($dataCustomer as $item) {
            Customer::create($item);
        }
    }
}
