<?php

use App\Models\Master\Letter;
use App\Models\Master\Timbangan;
use App\Models\User;

if ( !function_exists('getJumlahSurat') )
{
    function getJumlahSurat($id){
        $hasil = Letter::where("id_transport", $id)->get()->count(); 

        return $hasil;
    }
}

if ( !function_exists('getJumlahBerat') )
{
    function getJumlahBerat($id){
        $total = 0;
        $dataSurat = Letter::where("id_transport", $id)->get();

        foreach($dataSurat as $dt) {
            $berat = Timbangan::where("id_letter", $dt->id)->sum('berat_barang');
            $total = $total + $berat;
        }

        return converHasilSatuan($total);
    }
}

if ( !function_exists('getJumlahBeratLetter') )
{
    function getJumlahBeratLetter($id){
        $hasil = Timbangan::where("id_letter", $id)->sum('berat_barang');

        return converHasilSatuan($hasil);
    }
}

if ( !function_exists('getJumlahBarang') )
{
    function getJumlahBarang($id){
        $hasil = Timbangan::select('kode_barang')->where("id_letter", $id)->groupBy('kode_barang')->get()->count();

        return $hasil.' Barang';
    }
}

if ( !function_exists('getTotalBarang') )
{
    function getTotalBarang($id){
        $hasil = Timbangan::where("id_letter", $id)->count();

        return $hasil.' Barang';
    }
}

if ( !function_exists('getJumlahBeratLetterBarang') )
{
    function getJumlahBeratLetterBarang($id, $kodeBarang){
        $hasil = Timbangan::where("id_letter", $id)->where("kode_barang", $kodeBarang)->sum('berat_barang');

        return converHasilSatuan($hasil);
    }
}

if ( !function_exists('converHasilSatuan') )
{
    function converHasilSatuan($jumlah){
        if ($jumlah < 100) {
            $perhitungan = number_format($jumlah, 3, ',');
            $hasil = $perhitungan.' KG';
        } else if ($jumlah >= 100 && $jumlah < 1000) {
            $perhitungan = number_format($jumlah / 100, 3, ',');
            $hasil = $perhitungan.' KW';
        } else if ($jumlah >= 1000) {
            $perhitungan = number_format($jumlah / 1000, 3, ',');
            $hasil = $perhitungan.' TON';
        } else {
            $hasil = 0;
        }

        return $hasil;
    }
}


if ( !function_exists('getUser') )
{
    function getUser($id){
        $hasil = User::where("id", $id)->first();

        return $hasil;
    }
}

if ( !function_exists('getJenisUser') )
{
    function getJenisUser($id){
        $hasil = User::where("id", $id)->first();

        return $hasil;
    }
}

if ( !function_exists('getTanggalIndo') )
{
    function getTanggalIndo($tanggal){
        date_default_timezone_set("Asia/Bangkok");
        $bulan = array (
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 =>'Agustus',
            9 =>'September',
            10 =>'Oktober',
            11 =>'November',
            12 =>'Desember'
        );

        $pecahkan = explode('-', $tanggal);
			
        return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
    }
}


?>