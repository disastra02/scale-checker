<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Master\Letter;
use App\Models\Master\Timbangan;
use App\Models\Master\Transport;
use Illuminate\Http\Request;
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

            return view('web.timbangan.detail', $data);
        } catch (Throwable $e) {
            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('w-dashboard.index');
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
            return redirect()->route('w-dashboard.index');
        } catch (Throwable $e) {

            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('w-dashboard.index');
        }
    }
}
