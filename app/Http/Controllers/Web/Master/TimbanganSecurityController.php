<?php

namespace App\Http\Controllers\Web\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Letter;
use App\Models\Master\Timbangan;
use App\Models\Master\Transport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use Throwable;


class TimbanganSecurityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $data['page'] = 'security';

        return view('web.security.index', $data);
    }

    public function scopeData(Request $req)
    {
        if ($req->ajax()) {
            $user = Auth::user();
            $data = Transport::join('users', 'users.id', 'transports.created_by')->select('transports.*')->where('users.id_jenis', 3)->orderBy('id', 'DESC')->get();


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
                                <a class="btn btn-secondary btn-sm" href="'.route('w-cek-security.show', $item->id).'" title="Detail"><i class="fa-solid fa-circle-info"></i> &nbsp; Detail</a>
                                <a class="btn btn-warning btn-sm" href="'.route('w-cek-security.perbandingan', $item->id).'" title="Perbandingan"><i class="fa-solid fa-arrows-left-right"></i> &nbsp; Perbandingan</a>
                                <a class="btn btn-success btn-sm" href="'.route('w-cek-checker.printToExcel', $item->id).'" title="Tallysheet"><i class="fa-solid fa-file-excel"></i> &nbsp; Tallysheet</a>
                                <form action="'.route('w-cek-security.destroy', $item->id).'" method="POST">
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        try {
            $data['transport'] = Transport::where('id', $id)->first();
            $data['suratJalan'] = Letter::with(['customers'])->where('id_transport', $data['transport']->id)->orderBy('id', 'ASC')->get();
            
            return view('web.security.detail', $data);
        } catch (Throwable $e) {
            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('w-cek-security.index');
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

            return view('web.security.perbandingan', $data);
        } catch (Throwable $e) {
            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('w-cek-security.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
            return redirect()->route('w-cek-security.index');
        } catch (Throwable $e) {

            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('w-cek-security.index');
        }
    }
}
