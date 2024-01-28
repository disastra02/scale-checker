<?php

namespace App\Http\Controllers\Web\Report;

use App\Http\Controllers\Controller;
use App\Models\Master\Customer;
use App\Models\Master\Letter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class ReportCustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $data['page'] = 'report_customer';

        return view('web.report.customer.index', $data);
    }

    public function getJumlah(Request $req)
    {
        if ($req->ajax()) {
            $startDate = $req->startdate.' 00:00:00';
            $endDate = $req->enddate.' 23:59:59';
            $jumlahCustomer = Letter::join('users', 'users.id', 'letters.created_by')->where('users.id_jenis', 2)->select('id_customer')->whereBetween('letters.created_at', [$startDate, $endDate])->groupBy('id_customer')->get()->count();
            $totalCustomer = Customer::count();
            $customerTersimpan = $totalCustomer - $jumlahCustomer;

            return response()->json(['jumlah' => $jumlahCustomer, 'total' => $totalCustomer, 'tersimpan' => $customerTersimpan]);
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
                    $pelanggan = getCustomer($item->id);
                    $hasil = $pelanggan ? $pelanggan->name : '-';

                    return $hasil;
                })
                ->addColumn('alamat', function($item){
                    $pelanggan = getCustomer($item->id);
                    $hasil = $pelanggan ? $pelanggan->address : '-';

                    return $hasil;
                })
                ->addColumn('total', function($item){
                    return $item->total ?? 0;
                })
                ->rawColumns(['nama', 'alamat', 'total'])
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
            $data['tipe'] = $tipe == 1 ? 'Membeli' : ($tipe == 2 ? 'Belum' : 'Semua');
            $data['data'] = $this->getData($startDate, $endDate, $tipe);
            $namaFile = 'Laporan_Pelanggan_'.$req->startdate.'_'.$req->enddate.'_'.$data['tipe'].'.pdf';

            $pdf = Pdf::loadView('web.report.customer.pdf', $data);
            return $pdf->download($namaFile);
        } catch (Throwable $e) {
            Session::flash('error', 'Terjadi sesuatu kesalahan pada server.');
            return redirect()->route('r-customer.index');
        }
    }

    public function getData($startDate, $endDate, $tipe)
    {
        $data = Customer::select('customers.id', DB::raw('(SELECT COUNT(*) FROM letters JOIN users ON users.id = letters.created_by WHERE letters.created_at BETWEEN "'.$startDate.'" AND "'.$endDate.'" AND letters.id_customer = customers.id AND users.id_jenis != 1 GROUP BY letters.id_customer) AS total'))
            ->leftJoin('letters', 'letters.id_customer', 'customers.id')
            ->join('users', function($query) use ($tipe) {
                if ($tipe == 1) {
                    $query->on('users.id', 'letters.created_by');
                }
            })
            ->where(function($query) use ($tipe, $startDate, $endDate) {
                if ($tipe == 1) {
                    $query->whereNotNull('letters.id')->where('users.id_jenis', 2)->whereBetween('letters.created_at', [$startDate, $endDate]);
                } else if ($tipe == 2) {
                    $query->where(DB::raw('(SELECT COUNT(*) FROM letters JOIN users ON users.id = letters.created_by WHERE letters.created_at BETWEEN "'.$startDate.'" AND "'.$endDate.'" AND letters.id_customer = customers.id AND users.id_jenis != 1 GROUP BY letters.id_customer)'), '=', null);
                }
            })->groupBy('customers.id')->orderBy('total', 'DESC')->get();

        // $data = Letter::select('id_customer', DB::raw('COUNT(*) as total'))->whereBetween('created_at', [$startDate, $endDate])->groupBy('id_customer')->orderBy('total', 'DESC')->get();
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
