<?php

namespace App\Http\Controllers\Web\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Barang;
use App\Models\Master\Customer;
use App\Models\Master\Letter;
use App\Models\Master\Timbangan;
use App\Models\Master\Transport;
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
                    $html = '<div class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="'.route('w-cek-manual.show', $item->id).'">Detail</a></li>
                                        <li><a class="dropdown-item" href="'.route('w-cek-manual.perbandingan', $item->id).'">Perbandingan</a></li>
                                        <li>
                                            <form action="'.route('w-cek-manual.destroy', $item->id).'" method="POST">
                                                '.method_field("DELETE").'
                                                '.csrf_field().'

                                                <a class="dropdown-item delete-data" type="submit">Hapus</a>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
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
            return redirect()->route('w-cek-manual.index');
        } catch (Throwable $e) {
            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('w-cek-manual.index');
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
                }, 'customers'
            ])->where('id_transport', $data['transport']->id)->orderBy('id', 'ASC')->get();

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
            $data['kendaraan'] = Transport::where('created_by', '!=', $data['user']->id)->orderBy('id', 'DESC')->get();
            
            // Surat Jalan
            $data['suratJalan'] = Letter::with([
                'timbangans' => function ($query) {
                    $query->join('barangs', 'barangs.kode', 'timbangans.kode_barang');
                }
            ])->where('id_transport', $data['transport']->id)->orderBy('id', 'ASC')->get();

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
                $query->join('barangs', 'barangs.kode', 'timbangans.kode_barang');
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
