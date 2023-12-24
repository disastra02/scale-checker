<?php

namespace App\Http\Controllers\Web\Report;

use App\Http\Controllers\Controller;
use App\Models\Master\Transport;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class ReportCheckerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $data['page'] = 'report_checker';

        return view('web.report.checker.index', $data);
    }

    public function getJumlah(Request $req)
    {
        if ($req->ajax()) {
            $startDate = $req->startdate.' 00:00:00';
            $endDate = $req->enddate.' 23:59:59';
            
            $jumlahChecker = Transport::select('transports.created_by')->join('users', 'users.id', 'transports.created_by')->where('users.id_jenis', '!=', 1)->whereBetween('transports.created_at', [$startDate, $endDate])->groupBy('transports.created_by')->get()->count();
            $totalChecker = User::where('id_jenis', 2)->count();
            $tersimpan = $totalChecker - $jumlahChecker;

            return response()->json(['jumlah' => $jumlahChecker, 'total' => $totalChecker, 'tersimpan' => $tersimpan]);
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
                    $user = User::where('id', $item->id)->first();
                    $hasil = $user ? $user->name : '-';

                    return $hasil;
                })
                ->addColumn('total', function($item){
                    return $item->total ?? 0;
                })
                ->rawColumns(['nama', 'total'])
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
            $data['tipe'] = $tipe == 1 ? 'Aktif' : ($tipe == 2 ? 'Belum' : 'Semua');
            $data['data'] = $this->getData($startDate, $endDate, $tipe);
            $namaFile = 'Laporan_Checker_'.$req->startdate.'_'.$req->enddate.'_'.$data['tipe'].'.pdf';

            $pdf = Pdf::loadView('web.report.checker.pdf', $data);
            return $pdf->download($namaFile);
        } catch (Throwable $e) {
            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('r-checker.index');
        }
    }

    public function getData($startDate, $endDate, $tipe) 
    {
        $data = User::select('users.id', DB::raw('(SELECT COUNT(*) FROM transports WHERE transports.created_at BETWEEN "'.$startDate.'" AND "'.$endDate.'" AND transports.created_by = users.id GROUP BY transports.created_by) AS total'))
                ->leftJoin('transports', 'transports.created_by', 'users.id')
                ->where('users.id_jenis', '!=', 1)
                ->where(function($query) use ($tipe, $startDate, $endDate) {
                    if ($tipe == 1) {
                        $query->whereNotNull('transports.id')->whereBetween('transports.created_at', [$startDate, $endDate]);
                    } else if ($tipe == 2) {
                        $query->where(DB::raw('(SELECT COUNT(*) FROM transports WHERE transports.created_at BETWEEN "'.$startDate.'" AND "'.$endDate.'" AND transports.created_by = users.id GROUP BY transports.created_by)'), '=', null);
                    }
                })->groupBy('users.id')->orderBy('total', 'DESC')->get();

        // $data = Transport::select('transports.created_by', DB::raw('COUNT(*) as total'))->join('users', 'users.id', 'transports.created_by')->where('users.id_jenis', '!=', 1)->whereBetween('transports.created_at', [$startDate, $endDate])->groupBy('transports.created_by')->orderBy('total', 'DESC')->get();
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
