<?php

namespace App\Http\Controllers\Web\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Barang;
use App\Models\Master\Customer;
use App\Models\Master\Letter;
use App\Models\Master\Timbangan;
use App\Models\Master\Transport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class TimbanganController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $data['page'] = 'manual';

        return view('web.cek_manual.index', $data);
    }

    public function scopeData(Request $req)
    {
        if ($req->ajax()) {
            $user = Auth::user();
            $data = Transport::where('created_by', $user->id)->orderBy('id', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user', function($item){
                    return getUser($item->created_by) ? getUser($item->created_by)->name : '-'; 
                })
                ->addColumn('total', function($item){
                    $html = '<span class="badge text-bg-primary">'.getJumlahSurat($item->id).' Surat</span>&nbsp;<span class="badge text-bg-secondary">'.getJumlahCustomer($item->id).' Pelanggan</span>';

                    return $html;
                })
                ->addColumn('berat', function($item){
                    return getJumlahBerat($item->id); 
                })
                ->addColumn('tanggal', function($item){
                    return getTanggalTable($item->created_at->format('Y-m-d')).', '.$item->created_at->format('H:i'); 
                })
                ->addColumn('aksi', function($item){
                    $html = '<div class="btn-group" role="group">
                                <a class="btn btn-secondary btn-sm" href="'.route('w-cek-manual.show', $item->id).'" title="Detail"><i class="fa-solid fa-circle-info"></i> &nbsp; Detail</a>
                                <a class="btn btn-warning btn-sm" href="'.route('w-cek-manual.perbandingan', $item->id).'" title="Perbandingan"><i class="fa-solid fa-arrows-left-right"></i> &nbsp; Perbandingan</a>
                                <a class="btn btn-success btn-sm" href="'.route('w-cek-checker.printToExcel', $item->id).'" title="Tallysheet"><i class="fa-solid fa-file-excel"></i> &nbsp; Tallysheet</a>
                                <form action="'.route('w-cek-manual.destroy', $item->id).'" method="POST">
                                '.method_field("DELETE").'
                                '.csrf_field().'                                
                                    <a class="btn btn-danger btn-sm delete-data" type="submit" title="Hapus"><i class="fa-solid fa-trash"></i> &nbsp; Hapus</a>
                                </form>
                            </div>';

                    return $html;
                })
                ->rawColumns(['user', 'total', 'berat', 'tanggal', 'aksi'])
                ->make(true);
        }
    }

    public function create()
    {
        $data['barang'] = Barang::orderBy('id', 'DESC')->get();
        $data['customer'] = Customer::orderBy('name', 'ASC')->get();

        return view('web.cek_manual.create', $data);
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
            $dataAllNoPo = $req->input('nomer_po');
            $dataAllBarcode = $req->input('nomer_barcode');

            foreach ($dataAllSuratJalan as $keySurat => $dtSurat) {
                $suratJalan = Letter::create([
                    'no_surat' => strtoupper($req->input('surat_jalan.'.$keySurat)),
                    'no_po' => strtoupper($req->input('po.'.$keySurat)),
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
            return redirect()->route('w-cek-manual.index');
        } catch (Throwable $e) {
            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('w-cek-manual.index');
        }
    }

    public function show(string $id)
    {
        try {
            $data['transport'] = Transport::where('id', $id)->first();
            $data['suratJalan'] = Letter::with(['customers'])->where('id_transport', $data['transport']->id)->orderBy('id', 'ASC')->get();

            return view('web.cek_manual.detail', $data);
        } catch (Throwable $e) {
            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('w-cek-manual.index');
        }
    }

    public function perbandingan(string $id)
    {
        try {
             // Transport
             $data['user'] = Auth::user();
             $data['transport'] = Transport::where('id', $id)->first();
             $data['pembuat'] = User::orderBy('id_jenis', 'ASC')->get();
             $data['suratJalan'] = Letter::with(['customers'])->where('id_transport', $data['transport']->id)->orderBy('id', 'ASC')->get(); 

            return view('web.cek_manual.perbandingan', $data);
        } catch (Throwable $e) {
            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('w-cek-manual.index');
        }
    }

    public function perbandinganDetail(Request $req)
    {
        // Transport
        $id = $req->get('id');
        $data['transport'] = Transport::where('id', $id)->first();
        
        // Surat Jalan
        $data['suratJalan'] = Letter::with([
            'timbangans' => function ($query) {
                $query->join('barangs', 'barangs.kode', 'timbangans.kode_barang')->orderBy('barangs.name', 'ASC');;
            }
        ])->where('id_transport', $data['transport']->id)->orderBy('id', 'ASC')->get();

        return view('web.cek_manual.perbandingan_detail', $data);
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

    /**
     * Remove the specified resource from storage.
     */
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
            return redirect()->route('w-cek-manual.index');
        } catch (Throwable $e) {

            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('w-cek-manual.index');
        }
    }
}
