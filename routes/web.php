<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserAuthenticationController;
use App\Http\Controllers\RegisterCompanyOrProviderController;
// use App\Http\Controllers\ChartOfAccountsController;
// use App\Http\Controllers\BankAccountsController;
// use App\Http\Controllers\SubBankAccountsController;
// use App\Http\Controllers\SubSubAccountController;
// use App\Http\Controllers\MoreAccountsBelowController;
// use App\Http\Controllers\ManageGeneralEntriesController;


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
    if(Auth::check()) {
      //Login back to the set up route
      return Redirect::to('/list/beneficiaries');
    }else {
       return view('auth.login');
    }
})->name('login');


Route::post('/user/login', [UserAuthenticationController::class, 'loginUser'])
->name('login.custom');
Route::post('/user/logout', [UserAuthenticationController::class, 'logOutUser'])
->name('logout.user');

Route::post('/logout', function(){
    Auth::logout();
    return Redirect::to('/');
 })->name('logout');

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/register', [RegisterCompanyOrProviderController::class, 'index']);

