<?php

namespace App\Http\Controllers\Web\Report;

use App\Http\Controllers\Controller;
use App\Models\Master\Barang;
use App\Models\Master\Timbangan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class ReportBarangController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $data['page'] = 'report_barang';

        return view('web.report.barang.index', $data);
    }

    public function getJumlah(Request $req)
    {
        if ($req->ajax()) {
            $startDate = $req->startdate.' 00:00:00';
            $endDate = $req->enddate.' 23:59:59';
            $jumlahBarang = Timbangan::select('timbangans.kode_barang')->join('users', 'users.id', 'timbangans.created_by')->where('users.id_jenis', 2)->whereBetween('timbangans.created_at', [$startDate, $endDate])->groupBy('timbangans.kode_barang')->get()->count();
            $totalBarang = Barang::count();
            $barangTersimpan = $totalBarang - $jumlahBarang;

            return response()->json(['jumlah' => $jumlahBarang, 'total' => $totalBarang, 'tersimpan' => $barangTersimpan]);
        }
    }

    public function scopeData(Request $req)
    {
        if ($req->ajax()) {
            $startDate = $req->startdate.' 00:00:00';
            $endDate = $req->enddate.' 23:59:59';
            $tipe = $req->tipe;
            $data = $this->getData($startDate, $endDate, $tipe);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama', function($item){
                    $barang = getBarang($item->kode);
                    $hasil = $barang ? $barang->name : '-';

                    return $hasil;
                })
                ->addColumn('jumlah', function($item){
                    return $item->total ?? 0;
                })
                ->addColumn('total', function($item) use ($startDate, $endDate) {
                    $total = getJumlahBeratReport($item->kode, $startDate, $endDate);
                    return $total;
                })
                ->rawColumns(['nama', 'jumlah', 'total'])
                ->make(true);
        }
    }

    public function printToPdf(Request $req)
    {
        try {
            $startDate = $req->startdate.' 00:00:00';
            $endDate = $req->enddate.' 23:59:59';
            $tipe = $req->tipe;

            $data['tanggal'] = getTanggalIndo($req->startdate).' s/d '.getTanggalIndo($req->enddate);
            $data['tipe'] = $tipe == 1 ? 'Terjual' : ($tipe == 2 ? 'Tersedia' : 'Semua');
            $data['startDate'] = $startDate;
            $data['endDate'] = $endDate;
            $data['data'] = $this->getData($startDate, $endDate, $tipe);
            $namaFile = 'Laporan_Barang_'.$req->startdate.'_'.$req->enddate.'_'.$data['tipe'].'.pdf';

            $pdf = Pdf::loadView('web.report.barang.pdf', $data);
            return $pdf->download($namaFile);
        } catch (Throwable $e) {
            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('r-barang.index');
        }
    }

    public function getData($startDate, $endDate, $tipe) 
    {
        $data = Barang::select('barangs.kode', DB::raw('(SELECT COUNT(*) FROM timbangans JOIN users ON users.id = timbangans.created_by WHERE timbangans.created_at BETWEEN "'.$startDate.'" AND "'.$endDate.'" AND timbangans.kode_barang = barangs.kode AND users.id_jenis != 1 GROUP BY timbangans.kode_barang) AS total'))
                ->leftJoin('timbangans', 'timbangans.kode_barang', 'barangs.kode')
                ->join('users', function($query) use ($tipe) {
                    if ($tipe == 1) {
                        $query->on('users.id', 'timbangans.created_by');
                    }
                })
                ->where(function($query) use ($tipe, $startDate, $endDate) {
                    if ($tipe == 1) {
                        $query->whereNotNull('timbangans.id')->where('users.id_jenis', 2)->whereBetween('timbangans.created_at', [$startDate, $endDate]);
                    } else if ($tipe == 2) {
                        $query->where(DB::raw('(SELECT COUNT(*) FROM timbangans JOIN users ON users.id = timbangans.created_by WHERE timbangans.created_at BETWEEN "'.$startDate.'" AND "'.$endDate.'" AND timbangans.kode_barang = barangs.kode AND users.id_jenis != 1 GROUP BY timbangans.kode_barang)'), '=', null);
                    }
                })->groupBy('barangs.kode', 'barangs.id')->orderBy('total', 'DESC')->get();

        // $data = Timbangan::select('kode_barang', DB::raw('COUNT(*) as total'))->whereBetween('created_at', [$startDate, $endDate])->groupBy('kode_barang')->orderBy('total', 'DESC')->get();
        return $data;
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
    public function show($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
