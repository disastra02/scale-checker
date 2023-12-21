<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AfterLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->is_mobile) {
            return redirect()->route('home');
        } else {
            return redirect()->route('w-dashboard.index');
        }
    }
}
