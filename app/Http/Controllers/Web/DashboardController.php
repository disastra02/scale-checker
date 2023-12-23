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
        $data['tanggal'] = [];
        $data['jumlah'] = [];
        $data['page'] = 'dashboard';
        $data['user'] = Auth::user();
        $data['totalKendaraan'] = Transport::where('created_by', '!=', $data['user']->id)->count(); 
        $data['totalSurat'] = Letter::where('created_by', '!=', $data['user']->id)->count(); 
        $data['totalBerat'] = Timbangan::where('created_by', '!=', $data['user']->id)->sum('berat_barang'); 
        $data['totalChecker'] = User::where('id_jenis', 2)->count();
        $data['kendaraan'] = Transport::where('created_by', '!=', $data['user']->id)->orderBy('id', 'DESC')->get();

        for($jumlahTanggal; $jumlahTanggal >= 0; $jumlahTanggal--) {
            $day = date('Y-m-d', strtotime('-'.$jumlahTanggal.' days'));
            $total = Transport::where('created_by', '!=', $data['user']->id)->whereDate('created_at', $day)->count();

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
