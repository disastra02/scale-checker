<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Master\Letter;
use App\Models\Master\Timbangan;
use App\Models\Master\Transport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $jumlahTanggal = 6;
        $startDate = date('Y-m-d', strtotime('-6 days')).' 00:00:00';
        $endDate = date('Y-m-d').' 23:59:59';
        
        $data['tanggal'] = [];
        $data['jumlah'] = [];
        $data['page'] = 'dashboard';
        $data['user'] = Auth::user();
        $data['totalKendaraan'] = Transport::join('users', 'users.id', 'transports.created_by')->where('users.id_jenis', 2)->whereBetween('transports.created_at', [$startDate, $endDate])->count(); 
        $data['totalSurat'] = Letter::join('users', 'users.id', 'letters.created_by')->where('users.id_jenis', 2)->whereBetween('letters.created_at', [$startDate, $endDate])->count(); 
        $data['totalPelanggan'] = Letter::select('letters.id_transport', 'letters.id_customer')->join('users', 'users.id', 'letters.created_by')->where('users.id_jenis', 2)->whereBetween('letters.created_at', [$startDate, $endDate])->groupBy('letters.id_transport', 'letters.id_customer')->get()->count(); 
        $data['totalBerat'] = Timbangan::join('users', 'users.id', 'timbangans.created_by')->where('users.id_jenis', 2)->whereBetween('timbangans.created_at', [$startDate, $endDate])->sum('timbangans.berat_barang'); 
        $data['totalChecker'] = User::where('id_jenis', 2)->whereBetween('created_at', [$startDate, $endDate])->count();
        for($jumlahTanggal; $jumlahTanggal >= 0; $jumlahTanggal--) {
            $day = date('Y-m-d', strtotime('-'.$jumlahTanggal.' days'));
            $total = Transport::join('users', 'users.id', 'transports.created_by')->where('users.id_jenis', 2)->whereDate('transports.created_at', $day)->count();

            array_push($data['tanggal'], $day);
            array_push($data['jumlah'], $total);
        }

        return view('web.dashboard.index', $data);
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
