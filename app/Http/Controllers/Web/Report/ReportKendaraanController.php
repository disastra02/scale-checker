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

            $data = Transport::select('no_kendaraan', DB::raw('COUNT(*) as total'))->join('users', 'users.id', 'transports.created_by')->where('users.id_jenis', 2)->whereBetween('transports.created_at', [$startDate, $endDate])->groupBy('no_kendaraan')->orderBy('total', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('detail', function($item) use ($startDate, $endDate) {
                    $html = '<div class="btn-group" role="group">
                                <a class="btn btn-secondary btn-sm" href="'.route('r-kendaraan.show', $item->no_kendaraan).'?awal='.$startDate.'&akhir='.$endDate.'" title="Detail"><i class="fa-solid fa-circle-info"></i> &nbsp; Detail</a>
                            </div>';

                    return $html;
                })
                ->rawColumns(['detail'])
                ->make(true);
        }
    }

    public function printToPdf(Request $req)
    {
        try {
            $startDate = $req->startdate.' 00:00:00';
            $endDate = $req->enddate.' 23:59:59';

            $data['tanggal'] = getTanggalIndo($req->startdate).' s/d '.getTanggalIndo($req->enddate);
            $data['data'] = Transport::select('no_kendaraan', DB::raw('COUNT(*) as total'))->join('users', 'users.id', 'transports.created_by')->where('users.id_jenis', 2)->whereBetween('transports.created_at', [$startDate, $endDate])->groupBy('no_kendaraan')->orderBy('total', 'DESC')->get();
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

    public function show($id)
    {
        try {
            // $data['transport'] = Transport::where('id', $id)->first();
            // $data['suratJalan'] = Letter::with(['customers'])->where('id_transport', $data['transport']->id)->orderBy('id', 'ASC')->get();
            $data['kendaraan'] = $id;

            return view('web.report.kendaraan.detail', $data);
        } catch (Throwable $e) {
            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('r-kendaraan.index');
        }
    }

    public function scopeDataShow(Request $req)
    {
        if ($req->ajax()) {
            $kendaraan = $req->kendaraan;
            $startDate = $req->awal;
            $endDate = $req->akhir;

            $data = Transport::join('users', 'users.id', 'transports.created_by')->select('transports.*')->where('users.id_jenis', 2)->where('no_kendaraan', $kendaraan)->whereBetween('transports.created_at', [$startDate, $endDate])->orderBy('id', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tanggal', function($item){
                    $tangal = getTanggalIndo($item->created_at->format('Y-m-d'));

                    return $tangal;
                })
                ->addColumn('berat', function($item){
                    return getJumlahBerat($item->id); 
                })
                ->addColumn('total', function($item){
                    $html = '<span class="badge text-bg-primary">'.getJumlahSurat($item->id).' Surat</span>&nbsp;<span class="badge text-bg-secondary">'.getJumlahCustomer($item->id).' Pelanggan</span>';

                    return $html;
                })
                ->rawColumns(['tanggal', 'berat', 'total'])
                ->make(true);
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
    public function destroy($id)
    {
        //
    }
}
