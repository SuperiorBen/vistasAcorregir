<?php

use App\Http\Controllers\AP\UsuariosController;
use App\Http\Controllers\AP\DashboardController;
use App\Http\Controllers\AP\KycController;
use App\Http\Controllers\AP\TxapController;
use App\Http\Controllers\AP\LimitController;
use App\Http\Controllers\AP\WalletapController;

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

Route::group(['middleware' => 'auth'], function () {


    Route::get('/', function () {
        return view('ap/welcome');
    });
    
    
    Route::get('/s1m2', function () {
        return view('ap/s1m2');
    });
    Route::get('/topups', function () {
        return view('ap/topups');
    });
    Route::any('/usuarios', [UsuariosController::class, 'usuarios']);

    Route::any('/kyc/{dni?}', [KycController::class, 'search']);

});

Route::any('/dashboard', [DashboardController::class, 'dashboard']);



Route::get('/limit', function () {
    return view('ap/limit');
});
Route::any('/limit', [LimitController::class, 'limit']);

Route::get('/txap', function () {
    return view('ap/txap');
});
Route::any('/txap', [TxapController::class, 'search']);


Route::get('/walletap', function () {
    return view('ap/walletap');
});
Route::any('/walletap', [WalletapController::class, 'search']);


