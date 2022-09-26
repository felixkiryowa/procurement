<?php

use App\Http\Controllers\CompanyUsersController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserAuthenticationController;
use App\Http\Controllers\RegisterCompanyOrProviderController;
use App\Http\Controllers\ManageProviderAndCompaniesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProcurementPlanController;
use App\Http\Controllers\BidsInvitationsController;



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

      $user = User::select('id', 'user_role')->where('id', Auth::user()->id)->first();

      if($user->user_role == 'Provider') {
        return Redirect::to('/company/bids');

      }else if($user->user_role == 'Company') {
        return Redirect::to('/manage/company/users');

      }else if($user->user_role == 'Procurement Officer' ) {
        return Redirect::to('/manage/procurement/plans');

      }else {
        return Redirect::to('/manage/companies');
      }
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

Route::get('/activate/user/{id}', [UserAuthenticationController::class, 'activateUser'])->name('activate.user');
Route::get('/deactivate/user/{id}', [UserAuthenticationController::class, 'deActivateUser'])->name('deactivate.user');


Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/register', [RegisterCompanyOrProviderController::class, 'index']);
Route::post('/send/secret/code', [RegisterCompanyOrProviderController::class,
 'sendSecretCodeToEmail']);
 Route::post('/create/provider/or/company', [RegisterCompanyOrProviderController::class,
 'createProviderOrCompany']);
 Route::post('/update/provider/or/company', [RegisterCompanyOrProviderController::class,
 'updateProviderOrCompany']);
 Route::get('/registered/companies', [ManageProviderAndCompaniesController::class, 'index'])->middleware(('role:Super Systems Administrator'));
 Route::get('/company/bids', [HomeController::class, 'viewBids'])->middleware(('role:Provider'));
 Route::get('/manage/companies', [HomeController::class, 'viewManageCompanies'])->middleware(('role:Super Systems Administrator'));
 Route::get('/manage/service/providers', [HomeController::class, 'viewManageProvider'])->middleware(('role:Super Systems Administrator'));
 Route::get('/manage/all/company/users/{id}', [HomeController::class, 'viewManageCompanyUsers'])->middleware(('role:Super Systems Administrator'));
 Route::get('/company/or/provider/details/{id}', [HomeController::class,
 'providerOrCompanyDetails'])->middleware(('role:Super Systems Administrator'));
 Route::get('/manage/company/users', [CompanyUsersController::class, 'index'])->middleware('role:Company');
 Route::get('/activate/company/user/{id}', [CompanyUsersController::class, 'activate']);
 Route::get('/deactivate/company/user/{id}', [CompanyUsersController::class, 'deactivate']);

 //Change Password

 Route::get('/change/password', [UserController::class, 'changepasswordview']);
 Route::post('/store/password', [UserController::class, 'store']);

 //Procurement Plans
 Route::get('/manage/procurement/plans', [ProcurementPlanController::class, 'index'])->middleware('role:Procurement Officer,Company');
 Route::post('/create/procurement/plan', [ProcurementPlanController::class, 'store']);

 Route::post('/approve/procurement/plan/detail', [ProcurementPlanController::class, 'approveProcurementPlanDetail']);
 Route::post('/reject/procurement/plan/detail', [ProcurementPlanController::class, 'rejectProcurementPlanDetail']);

 Route::post('/update/procurement/plan', [ProcurementPlanController::class, 'updatePlan']);
// Procurement Plan Details
 Route::post('/procurement_plan/details/approvers', [ProcurementPlanController::class, 'createProcurementDetailsApprovers']);

 Route::get('/submit/procurement/detail/for/approval/{id}', [ProcurementPlanController::class,
  'submitProcumentPlanDetailForApproval']);
 Route::get('/get/who/to/approve/step/{id}', [ProcurementPlanController::class, 'validateIfLoggedInUserIsApprover']);
 Route::get('/manage/procurement_plan/details/{step_id}', [ProcurementPlanController::class, 'details']);
 Route::post('/create/procurement_plan/detail', [ProcurementPlanController::class, 'detailstore']);
 Route::post('/update/procurement_plan/detail', [ProcurementPlanController::class, 'updateDetails']);


 //Bids

 Route::get('/manage/bid/invitations', [BidsInvitationsController::class, 'index']);
 Route::post('/create/bid/invitation', [BidsInvitationsController::class, 'store']);
 Route::post('/update/bid/invitation', [BidsInvitationsController::class, 'update']);






