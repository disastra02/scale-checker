<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
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
        //
    }

    public function scopeData(Request $req)
    {
        if ($req->ajax()) {
            $user = Auth::user();
            $data = Transport::where('created_by', '!=', $user->id)->orderBy('id', 'DESC')->get();

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
                                        <li><a class="dropdown-item" href="'.route('w-timbangan.show', $item->id).'">Detail</a></li>
                                        <li><a class="dropdown-item" href="'.route('w-timbangan.perbandingan', $item->id).'">Perbandingan</a></li>
                                        <li>
                                            <form action="'.route('w-timbangan.destroy', $item->id).'" method="POST">
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
                }, 'customers'
            ])->where('id_transport', $data['transport']->id)->orderBy('id', 'ASC')->get();

            return view('web.timbangan.detail', $data);
        } catch (Throwable $e) {
            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('w-dashboard.index');
        }
    }

    public function perbandingan(string $id)
    {
        try {
            // Transport
            $data['user'] = Auth::user();
            $data['transport'] = Transport::where('id', $id)->first();
            $data['kendaraan'] = Transport::where('created_by', $data['user']->id)->orderBy('id', 'DESC')->get();
            
            // Surat Jalan
            $data['suratJalan'] = Letter::with([
                'timbangans' => function ($query) {
                    $query->join('barangs', 'barangs.kode', 'timbangans.kode_barang');
                }
            ])->where('id_transport', $data['transport']->id)->orderBy('id', 'ASC')->get();

            return view('web.timbangan.perbandingan', $data);
        } catch (Throwable $e) {
            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('w-cek-manual.index');
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
