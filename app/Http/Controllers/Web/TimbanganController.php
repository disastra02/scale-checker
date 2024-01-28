<?php

namespace App\Http\Controllers\Web;

use App\Exports\TimbangansExport;
use App\Http\Controllers\Controller;
use App\Models\Master\Letter;
use App\Models\Master\Timbangan;
use App\Models\Master\Transport;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;
use Yajra\DataTables\Facades\DataTables;
use ZipArchive;

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
            $startDate = date('Y-m-d', strtotime('-6 days')).' 00:00:00';
            $endDate = date('Y-m-d').' 23:59:59';

            $data = Transport::join('users', 'users.id', 'transports.created_by')->select('transports.*')->where('users.id_jenis', 2)->whereBetween('transports.created_at', [$startDate, $endDate])->orderBy('id', 'DESC')->get();

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
                                <a class="btn btn-secondary btn-sm" href="'.route('w-cek-checker.show', $item->id).'" title="Detail"><i class="fa-solid fa-circle-info"></i> &nbsp; Detail</a>
                                <a class="btn btn-warning btn-sm" href="'.route('w-cek-checker.perbandingan', $item->id).'" title="Perbandingan"><i class="fa-solid fa-arrows-left-right"></i> &nbsp; Perbandingan</a>
                                <a class="btn btn-success btn-sm" href="'.route('w-cek-checker.printToExcel', $item->id).'" title="Tallysheet"><i class="fa-solid fa-file-excel"></i> &nbsp; Tallysheet</a>
                                <form action="'.route('w-cek-checker.destroy', $item->id).'" method="POST">
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
            $data['transport'] = Transport::where('id', $id)->first();
            $data['suratJalan'] = Letter::with(['customers'])->where('id_transport', $data['transport']->id)->orderBy('id', 'ASC')->get();
            
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
            $data['pembuat'] = User::orderBy('id_jenis', 'ASC')->get();
            $data['suratJalan'] = Letter::with(['customers'])->where('id_transport', $data['transport']->id)->orderBy('id', 'ASC')->get();

            return view('web.timbangan.perbandingan', $data);
        } catch (Throwable $e) {
            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('w-cek-manual.index');
        }
    }

    public function getOption(Request $req)
    {
        try {
            $id = $req->get('id');
            $data['kendaraan'] = Transport::where('created_by', $id)->orderBy('id', 'DESC')->get();
            
            return view('web.widget.option_perbandingan', $data);
        } catch (Throwable $e) {
            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('w-dashboard.index');
        }
    }

    public function printToExcel(string $id)
    {
        try {
            // Transport
            $data['user'] = Auth::user();
            $transport = Transport::where('id', $id)->first();
            $data['transport'] = $transport;
            $suratJalan = Letter::with(['customers'])->where('id_transport', $data['transport']->id)->orderBy('id', 'ASC');

            if ($suratJalan->count() > 1) {
                $zipFileName = 'excel\Kendaraan_'.str_replace(' ', '-', $transport->no_kendaraan).'_'.str_replace(' ', '-', getUser($transport->created_by)->name ?? 'No_Name').'_'.$transport->created_at->format('Y-m-d').'.zip';
                $zip = new ZipArchive;

                $arrayFile = [];
                if ($zip->open(public_path($zipFileName), ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                    foreach ($suratJalan->get() as $index => $item) {
                        $nomor = $index + 1;
                        $data['suratJalan'] = $item;
                        $namaFile = 'Kendaraan_'.str_replace(' ', '-', $transport->no_kendaraan).'_'.str_replace(' ', '-', getUser($transport->created_by)->name ?? 'No_Name').'_'.$transport->created_at->format('Y-m-d').'_Surat_'.$nomor.'.xlsx';

                        array_push($arrayFile, $namaFile);
                        $excelFilePath = 'excel\exports\\' . $namaFile;
                        Excel::store(new TimbangansExport($data), $excelFilePath, 'real_public');
                        $zip->addFile(public_path($excelFilePath), basename(public_path($excelFilePath)));
                    }

                    $zip->close();

                    foreach ($arrayFile as $item) {
                        $excelFilePath = 'excel\exports\\' . $item;
                        Storage::disk('real_public')->delete($excelFilePath);
                    }
                }

                return response()->download(public_path($zipFileName))->deleteFileAfterSend(true);
            } else {
                $data['suratJalan'] = $suratJalan->first();
                $namaFile = 'Kendaraan_'.str_replace(' ', '-', $transport->no_kendaraan).'_'.str_replace(' ', '-', getUser($transport->created_by)->name ?? 'No_Name').'_'.$transport->created_at->format('Y-m-d').'.xlsx';

                return Excel::download(new TimbangansExport($data), $namaFile);
            }
        } catch (Throwable $e) {
            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('w-dashboard.index');
        }
    }

    public function printToPdf(string $id)
    {
        try {
            // Transport
            $transport = Transport::where('id', $id)->first();
            $data['transport'] = $transport;
            
            // Surat Jalan
            $data['suratJalan'] = Letter::with([
                'timbangans' => function ($query) {
                    $query->join('barangs', 'barangs.kode', 'timbangans.kode_barang')->orderBy('barangs.name', 'ASC');;
                }, 'customers'
            ])->where('id_transport', $transport->id)->orderBy('id', 'ASC')->get();

            $namaFile = 'Kendaraan_'.str_replace(' ', '-', $transport->no_kendaraan).'_'.str_replace(' ', '-', getUser($transport->created_by)->name ?? 'No_Name').'_'.$transport->created_at->format('Y-m-d').'.pdf';

            $pdf = Pdf::loadView('web.timbangan.pdf', $data);
            return $pdf->download($namaFile);
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
