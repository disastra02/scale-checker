<?php

namespace App\Http\Controllers\Web\Report;

use App\Http\Controllers\Controller;
use App\Models\Master\Transport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class ReportKendaraanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $data['page'] = 'report_kendaraan';

        return view('web.report.kendaraan.index', $data);
    }

    public function scopeData(Request $req)
    {
        if ($req->ajax()) {
            $startDate = $req->startdate.' 00:00:00';
            $endDate = $req->enddate.' 23:59:59';

            $data = Transport::select('no_kendaraan', DB::raw('COUNT(*) as total'))->join('users', 'users.id', 'transports.created_by')->where('users.id_jenis', '!=', 1)->whereBetween('transports.created_at', [$startDate, $endDate])->groupBy('no_kendaraan')->orderBy('total', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function printToPdf(Request $req)
    {
        try {
            $startDate = $req->startdate.' 00:00:00';
            $endDate = $req->enddate.' 23:59:59';

            $data['tanggal'] = getTanggalIndo($req->startdate).' s/d '.getTanggalIndo($req->enddate);
            $data['data'] = Transport::select('no_kendaraan', DB::raw('COUNT(*) as total'))->join('users', 'users.id', 'transports.created_by')->where('users.id_jenis', '!=', 1)->whereBetween('transports.created_at', [$startDate, $endDate])->groupBy('no_kendaraan')->orderBy('total', 'DESC')->get();
            $namaFile = 'Laporan_Kendaraan_'.$req->startdate.'_'.$req->enddate.'.pdf';

            $pdf = Pdf::loadView('web.report.kendaraan.pdf', $data);
            return $pdf->download($namaFile);
        } catch (Throwable $e) {
            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('r-kendaraan.index');
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
