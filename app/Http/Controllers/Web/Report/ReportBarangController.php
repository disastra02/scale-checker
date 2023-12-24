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

        // $data = Timbangan::select('kode_barang', DB::raw('COUNT(*) as total'))->groupBy('kode_barang')->orderBy('total', 'DESC')->get();

        return view('web.report.barang.index', $data);
    }

    public function getJumlah(Request $req)
    {
        if ($req->ajax()) {
            $startDate = $req->startdate.' 00:00:00';
            $endDate = $req->enddate.' 23:59:59';
            $jumlahBarang = Timbangan::select('kode_barang')->whereBetween('created_at', [$startDate, $endDate])->groupBy('kode_barang')->get()->count();
            $totalBarang = Barang::count();

            return response()->json(['jumlah' => $jumlahBarang, 'total' => $totalBarang]);
        }
    }

    public function scopeData(Request $req)
    {
        if ($req->ajax()) {
            $startDate = $req->startdate.' 00:00:00';
            $endDate = $req->enddate.' 23:59:59';
            $data = Timbangan::select('kode_barang', DB::raw('COUNT(*) as total'))->whereBetween('created_at', [$startDate, $endDate])->groupBy('kode_barang')->orderBy('total', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama', function($item){
                    $barang = Barang::where('kode', $item->kode_barang)->first();
                    $hasil = $barang ? $barang->name : '-';

                    return $hasil;
                })
                ->rawColumns(['nama'])
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
