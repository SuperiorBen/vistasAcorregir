<?php

use App\Http\Controllers\BitgoController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\KycsoloController;
use App\Http\Controllers\TxController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TelefonoController;
use App\Http\Controllers\AmlController;
use App\Http\Controllers\AmlprintController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\BalanceGraphController;
use App\Http\Controllers\LightningController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Request;

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
        return view('welcome');
    });

    Route::get('/s1m2', function () {
        return view('s1m2');
    });
    
    Route::get('/users', function () {
        return view('users');
    });

    Route::any('/kyc/{dni?}', [KycController::class, 'search']);
    Route::any('/kycsolo/{id?}', [KycsoloController::class, 'search']);

    Route::get('/tx', function () {
        return view('tx');
    });

    Route::post('/tx', [TxController::class, 'search']);

    Route::get('/wallet', function () {
        return view('wallet');
    });

    Route::post('/wallet', [WalletController::class, 'search']);


    Route::get('/telefono', function () {
        return view('telefono');
    });

    Route::post('/telefono', [TelefonoController::class, 'search']);


    Route::get('/aml', function () {
        return view('aml');
    });

    Route::post('/aml', [AmlController::class, 'search']);


    Route::get('/amlprint', function () {
        return view('amlprint');
    });

    Route::post('/amlprint', [AmlprintController::class, 'search']);



    Route::get('/lightning', function () {
        return view('lightning');
    });

    Route::post('/lightning', [LightningController::class, 'search']);


    Route::get('/bitgo/pendingApprovals', [BitgoController::class, 'pendingApprovals']);

    Route::get('/topups', function () {
        return view('topups');
    });

    Route::any('/balance', [BalanceController::class, 'search']);

    Route::any('/balancegraph', [BalanceGraphController::class, 'search']);

});

Route::get('/login', function () {
    return Socialite::driver('google')->redirect();
})->name('login');

Route::get('/logout', function (Request $request) {
    Auth::logout();
    return redirect('/');
})->name('logout');


Route::get('/auth/callback', function () {
    $user = Socialite::driver('google')->user();

    $email = $user->getEmail();
    if( filter_var($email, FILTER_VALIDATE_EMAIL ) ) {
        // split on @ and return last value of array (the domain)
        $email_sections = explode('@', $email);
        $domain = array_pop($email_sections);
     
    }

    if ($domain <> 'chivowallet.com') {
        return redirect('login');
    }
    
    $existingUser = User::firstOrCreate(
        [
            'email' => $user->getEmail(),
        ],
        [
            'name' => $user->getName(),
            'password' => 'gmail',
        ]
    );

    try {
        Auth::login($existingUser);
        return redirect('/');
    } catch (Exception $e) {
        return redirect('login');
    }
});
