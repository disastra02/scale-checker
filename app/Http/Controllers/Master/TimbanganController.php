<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Barang;
use App\Models\Master\Customer;
use App\Models\Master\Letter;
use App\Models\Master\Timbangan;
use App\Models\Master\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Throwable;

class TimbanganController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        //
    }

    public function create()
    {
        $data['dataBarang'] = Barang::get()->keyBy('kode');
        $data['customer'] = Customer::orderBy('name', 'ASC')->get();
        return view('master.timbangan.create', $data);
    }

    public function store(Request $req)
    {
        try {

            // User created
            $user = Auth::user();

            // Validation

            // Save transport
            $transport = Transport::create([
                'no_kendaraan' => strtoupper($req->input('nomor_kendaraan')),
                'created_by' => $user->id
            ]);

            // Surat Jalan & Timbangan
            $dataAllSuratJalan = $req->input('nomer_surat');
            $dataAllBarcode = $req->input('nomer_barcode');

            foreach ($dataAllSuratJalan as $keySurat => $dtSurat) {
                $suratJalan = Letter::create([
                    'no_surat' => strtoupper($req->input('surat_jalan.'.$keySurat)),
                    'id_transport' => $transport->id,
                    'id_customer' => $req->input('customer.'.$keySurat),
                    'created_by' => $user->id
                ]);

                if ($dataAllBarcode) {
                    foreach ($dataAllBarcode as $keyBarcode => $dtBarcode) {
                        if ($dtSurat != $dtBarcode) {
                            break;
                        } 
        
                        Timbangan::create([
                            'id_letter' => $suratJalan->id,
                            'kode_barang' => $req->input('kode_barang.'.$keyBarcode),
                            'berat_barang' => $req->input('berat_barang.'.$keyBarcode),
                            'created_by' => $user->id
                        ]);
        
                        unset($dataAllBarcode[$keyBarcode]);
                    }
                }
            }

            Session::flash('success', 'Berhasil menambahkan data.');
            return redirect()->route('home');
        } catch (Throwable $e) {
            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('home');
        }
    }

    public function show(string $id)
    {
        try {
            // Transport
            $data['transport'] = Transport::where('id', $id)->first();
            
            // Surat Jalan
            $data['suratJalan'] = Letter::with([
                'timbangans' => function ($query) {
                    $query->join('barangs', 'barangs.kode', 'timbangans.kode_barang');
                }
            ])->where('id_transport', $data['transport']->id)->orderBy('id', 'ASC')->get();

            return view('master.timbangan.detail', $data);
        } catch (Throwable $e) {
            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('home');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        try {
            $transport = Transport::where('id', $id);
            $dataSurat = Letter::where("id_transport", $transport->first()->id);

            foreach($dataSurat->get() as $dt) {
                // Remove Timbangan
                Timbangan::where("id_letter", $dt->id)->delete();
                
            }
            // Remove Letter
            $dataSurat->delete();

            // Remove Transport
            $transport->delete();

            Session::flash('success', 'Berhasil menghapus data.');
            return redirect()->route('home');
        } catch (Throwable $e) {

            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('home');
        }
    }
}
