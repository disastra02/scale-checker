<?php

namespace App\Http\Controllers\Web\Report;

use App\Http\Controllers\Controller;
use App\Models\Master\Customer;
use App\Models\Master\Letter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $jumlahCustomer = Letter::select('id_customer')->whereBetween('created_at', [$startDate, $endDate])->groupBy('id_customer')->get()->count();
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

            $data = Customer::select('customers.id', DB::raw('(SELECT COUNT(*) FROM letters WHERE letters.created_at BETWEEN "'.$startDate.'" AND "'.$endDate.'" AND letters.id_customer = customers.id GROUP BY letters.id_customer) AS total'))
            ->leftJoin('letters', 'letters.id_customer', 'customers.id')
            ->where(function($query) use ($tipe, $startDate, $endDate) {
                if ($tipe == 1) {
                    $query->whereNotNull('letters.id')->whereBetween('letters.created_at', [$startDate, $endDate]);
                } else if ($tipe == 2) {
                    $query->where(DB::raw('(SELECT COUNT(*) FROM letters WHERE letters.created_at BETWEEN "'.$startDate.'" AND "'.$endDate.'" AND letters.id_customer = customers.id GROUP BY letters.id_customer)'), '=', null);
                }
            })->groupBy('customers.id')->orderBy('total', 'DESC')->get();

            // $data = Letter::select('id_customer', DB::raw('COUNT(*) as total'))->whereBetween('created_at', [$startDate, $endDate])->groupBy('id_customer')->orderBy('total', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama', function($item){
                    $pelanggan = Customer::where('id', $item->id)->first();
                    $hasil = $pelanggan ? $pelanggan->name : '-';

                    return $hasil;
                })
                ->addColumn('alamat', function($item){
                    $pelanggan = Customer::where('id', $item->id)->first();
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
