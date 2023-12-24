<?php

use App\Http\Controllers\AfterLoginController;
use App\Http\Controllers\Master\TimbanganController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\Master\BarangController;
use App\Http\Controllers\Web\Master\CustomerController;
use App\Http\Controllers\Web\Master\TimbanganController as MasterTimbanganController;
use App\Http\Controllers\Web\Master\UsersController;
use App\Http\Controllers\Web\Report\ReportBarangController;
use App\Http\Controllers\Web\Report\ReportCheckerController;
use App\Http\Controllers\Web\Report\ReportCustomerController;
use App\Http\Controllers\Web\Report\ReportKendaraanController;
use App\Http\Controllers\Web\TimbanganController as WebTimbanganController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/after-login', [AfterLoginController::class, 'index'])->name('after-login');

// Timbangan
Route::resource('timbangan', TimbanganController::class);

// Website
// Dashboard
Route::get('w-dashboard', [DashboardController::class, 'index'])->name('w-dashboard.index');

// Checker
Route::get('w-timbangan/scope-data', [WebTimbanganController::class, 'scopeData'])->name('w-timbangan.scopeData');
Route::get('w-timbangan/{id}/perbandingan', [WebTimbanganController::class, 'perbandingan'])->name('w-timbangan.perbandingan');
Route::resource('w-timbangan', WebTimbanganController::class);

// Manual
Route::get('w-cek-manual/scope-data', [MasterTimbanganController::class, 'scopeData'])->name('w-cek-manual.scopeData');
Route::get('w-cek-manual/{id}/perbandingan', [MasterTimbanganController::class, 'perbandingan'])->name('w-cek-manual.perbandingan');
Route::get('w-cek-manual/perbandingan-detail', [MasterTimbanganController::class, 'perbandinganDetail'])->name('w-cek-manual.perbandinganDetail');
Route::resource('w-cek-manual', MasterTimbanganController::class);

// Master 
// Barang
Route::get('m-barang/scope-data', [BarangController::class, 'scopeData'])->name('m-barang.scopeData');
Route::resource('m-barang', BarangController::class);

// User
Route::get('m-users/scope-data', [UsersController::class, 'scopeData'])->name('m-users.scopeData');
Route::resource('m-users', UsersController::class);

// Customer
Route::get('m-customer/scope-data', [CustomerController::class, 'scopeData'])->name('m-customer.scopeData');
Route::resource('m-customer', CustomerController::class);

// Report
// Barang
Route::get('r-barang/scope-data', [ReportBarangController::class, 'scopeData'])->name('r-barang.scopeData');
Route::get('r-barang/get-jumlah', [ReportBarangController::class, 'getJumlah'])->name('r-barang.getJumlah');
Route::resource('r-barang', ReportBarangController::class);

// Customer
Route::get('r-customer/scope-data', [ReportCustomerController::class, 'scopeData'])->name('r-customer.scopeData');
Route::get('r-customer/get-jumlah', [ReportCustomerController::class, 'getJumlah'])->name('r-customer.getJumlah');
Route::resource('r-customer', ReportCustomerController::class);

// Customer
Route::get('r-checker/scope-data', [ReportCheckerController::class, 'scopeData'])->name('r-checker.scopeData');
Route::get('r-checker/get-jumlah', [ReportCheckerController::class, 'getJumlah'])->name('r-checker.getJumlah');
Route::resource('r-checker', ReportCheckerController::class);

// Kendaraan
Route::get('r-kendaraan/scope-data', [ReportKendaraanController::class, 'scopeData'])->name('r-kendaraan.scopeData');
Route::resource('r-kendaraan', ReportKendaraanController::class);

