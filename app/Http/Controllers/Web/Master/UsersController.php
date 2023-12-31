<?php

namespace App\Http\Controllers\Web\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Web\JenisUser;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $data['page'] = 'users';

        return view('web.master.users.index', $data);
    }

    public function scopeData(Request $req)
    {
        if ($req->ajax()) {
            $data = User::with('jenisUsers')->orderBy('id', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jenis', function($item){
                    return $item->jenisUsers->name; 
                })
                ->addColumn('aksi', function($item){
                    $btnDelete = $item->id_jenis != 1 ? '<li><a class="dropdown-item" href="#">Hapus</a></li>' : '';

                    $html = '<div class="btn-group">
                                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Detail</a></li>
                                    <li><a class="dropdown-item" href="#">Perbarui</a></li>
                                    '.$btnDelete.'
                                </ul>
                            </div>';

                    return $html;
                })
                ->rawColumns(['jenis', 'aksi'])
                ->make(true);
        }
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
