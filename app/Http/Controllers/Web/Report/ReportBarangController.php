<?php

namespace App\Http\Controllers\Web\Report;

use App\Http\Controllers\Controller;
use App\Models\Master\Barang;
use App\Models\Master\Timbangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $jumlahBarang = Timbangan::select('kode_barang')->whereBetween('created_at', [$startDate, $endDate])->groupBy('kode_barang')->get()->count();
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

            $data = Barang::select('barangs.kode', DB::raw('(SELECT COUNT(*) FROM timbangans WHERE timbangans.created_at BETWEEN "'.$startDate.'" AND "'.$endDate.'" AND timbangans.kode_barang = barangs.kode GROUP BY timbangans.kode_barang) AS total'))
                ->leftJoin('timbangans', 'timbangans.kode_barang', 'barangs.kode')
                ->where(function($query) use ($tipe, $startDate, $endDate) {
                    if ($tipe == 1) {
                        $query->whereNotNull('timbangans.id')->whereBetween('timbangans.created_at', [$startDate, $endDate]);
                    } else if ($tipe == 2) {
                        $query->where(DB::raw('(SELECT COUNT(*) FROM timbangans WHERE timbangans.created_at BETWEEN "'.$startDate.'" AND "'.$endDate.'" AND timbangans.kode_barang = barangs.kode GROUP BY timbangans.kode_barang)'), '=', null);
                    }
                })->groupBy('barangs.id')->orderBy('total', 'DESC')->get();

            // $data = Timbangan::select('kode_barang', DB::raw('COUNT(*) as total'))->whereBetween('created_at', [$startDate, $endDate])->groupBy('kode_barang')->orderBy('total', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama', function($item){
                    $barang = Barang::where('kode', $item->kode)->first();
                    $hasil = $barang ? $barang->name : '-';

                    return $hasil;
                })
                ->addColumn('total', function($item){
                    return $item->total ?? 0;
                })
                ->rawColumns(['nama', 'total'])
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