<?php

namespace App\Http\Controllers;

use App\Models\Master\Letter;
use App\Models\Master\Timbangan;
use App\Models\Master\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data['user'] = Auth::user();
        $data['totalKendaraan'] = Transport::where("created_by", $data['user']->id)->whereDate("created_at", date('Y-m-d'))->count(); 
        $data['totalSurat'] = Letter::where("created_by", $data['user']->id)->whereDate("created_at", date('Y-m-d'))->count(); 
        $data['totalBerat'] = Timbangan::where("created_by", $data['user']->id)->whereDate("created_at", date('Y-m-d'))->sum('berat_barang'); 
        $data['kendaraan'] = Transport::where("created_by", $data['user']->id)->whereDate("created_at", date('Y-m-d'))->orderBy('id', 'DESC')->get();

        return view('dashboard.index', $data);
    }
}
