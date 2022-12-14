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
        return Redirect::to('/company/tender/notices');

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
 Route::get('/registered/companies', [ManageProviderAndCompaniesController::class, 'index'])->middleware('role:Super Systems Administrator');
 Route::get('/company/tender/notices', [HomeController::class, 'viewBidsInvitationsByProviders'])->middleware('role:Provider');
 Route::get('/company/tender/notice/details/{id}', [HomeController::class, 'getBidInviteDetails'])->middleware('role:Provider');

 Route::get('/check/user/submitted/bid/already/{id}', [HomeController::class, 'checkIfBidIsAlreadySubmitted']);
 Route::get('/manage/companies', [HomeController::class, 'viewManageCompanies'])->middleware('role:Super Systems Administrator');
 Route::get('/manage/service/providers', [HomeController::class, 'viewManageProvider'])->middleware('role:Super Systems Administrator');
 Route::get('/manage/all/company/users/{id}', [HomeController::class, 'viewManageCompanyUsers'])->middleware('role:Super Systems Administrator');
 Route::get('/company/or/provider/details/{id}', [HomeController::class,
 'providerOrCompanyDetails'])->middleware('role:Super Systems Administrator');
 Route::get('/manage/submitted/bids', [HomeController::class, 'allUserSubmittedBids'])
 ->middleware('role:Provider');
 Route::get('/submitted/bid/details/{id}', [HomeController::class, 'providerSubmittedBidDetails'])
 ->middleware('role:Provider');

 Route::get('/edit/submitted/bid/{id}', [HomeController::class, 'viewAndEditSubmittedBid'])
 ->middleware('role:Provider');
 Route::get('edit/company/settings', [HomeController::class, 'editCompanyProfile'])
 ->middleware('role:Company');

 Route::get('/manage/company/users', [CompanyUsersController::class, 'index'])->middleware('role:Company');
 Route::get('/activate/company/user/{id}', [CompanyUsersController::class, 'activate']);
 Route::get('/deactivate/company/user/{id}', [CompanyUsersController::class, 'deactivate']);

 //Change Password

 Route::get('/change/password', [UserController::class, 'changepasswordview']);
 Route::post('/store/password', [UserController::class, 'store']);

 //Procurement Plans
 Route::get('/manage/procurement/plans', [ProcurementPlanController::class, 'index'])
 ->middleware('role:Procurement Officer,Company');
 Route::post('/create/procurement/plan', [ProcurementPlanController::class, 'store']);

 Route::post('/approve/procurement/plan/detail', [ProcurementPlanController::class, 'approveProcurementPlanDetail']);
 Route::post('/reject/procurement/plan/detail', [ProcurementPlanController::class, 'rejectProcurementPlanDetail']);

 Route::post('/update/procurement/plan', [ProcurementPlanController::class, 'updatePlan']);
// Procurement Plan Details
 Route::post('/procurement_plan/details/approvers', [ProcurementPlanController::class, 'createProcurementDetailsApprovers']);
 Route::post('/procurement_plan/update/details/approvers', [ProcurementPlanController::class, 'updateCompanyProcurementApprovers']);

 Route::get('/submit/procurement/detail/for/approval/{id}', [ProcurementPlanController::class,
  'submitProcumentPlanDetailForApproval']);
 Route::get('/get/who/to/approve/step/{id}', [ProcurementPlanController::class, 'validateIfLoggedInUserIsApprover']);
 Route::get('/manage/procurement_plan/details/{step_id}', [ProcurementPlanController::class, 'details']);
 Route::get('/view/procurement/plan/details/{id}', [ProcurementPlanController::class, 'viewProcurementPlanDetails']);

 Route::post('/create/procurement_plan/detail', [ProcurementPlanController::class, 'detailstore']);
 Route::post('/update/procurement_plan/detail', [ProcurementPlanController::class, 'updateDetails']);

 //Bids
 Route::get('/manage/bid/invitations', [BidsInvitationsController::class, 'index'])
 ->middleware('role:Company,Procurement Officer');
 Route::get('/bids/received', [BidsInvitationsController::class, 'showAllSubmittedBids'])
 ->middleware('role:Company,Procurement Officer');

 Route::get('/view/submitted.bid/details/{id}', [BidsInvitationsController::class, 'viewSubmittedBidDetails'])
 ->middleware('role:Company,Procurement Officer');

 Route::post('/create/bid/invitation', [BidsInvitationsController::class, 'store']);
 Route::post('/submit/provider/bid', [BidsInvitationsController::class, 'submitProviderBid']);
 Route::post('/update/provider/bid', [BidsInvitationsController::class, 'updateSubmittedBid']);
 Route::post('/submit/best/evaluated/bidder', [BidsInvitationsController::class,
 'submitBestEvaluatedBidder']);

 Route::get('/get/all/submitted/bids/{id}', [BidsInvitationsController::class, 'getSubmittedBids']);
 Route::get('/get/submitted/bids/document/{submitted_bid_id}', [BidsInvitationsController::class,
 'getSubmittedBidDetails']);
 Route::post('/download/uploaded/file', [BidsInvitationsController::class,
 'dowloadUploadedDocumentFile']);
 Route::get('/best/evaluated/bidders', [BidsInvitationsController::class, 'viewBestEvaluatedBiddersList']);
 Route::get('/submit/best/evaluated/bids', [BidsInvitationsController::class, 'formToAddBestEvaluatedBidder']);

 Route::get('/get/tender/notice/submitted/bids/{id}', [BidsInvitationsController::class,
 'subSubmittedBidsOnTenderNotice']);

 Route::post('/update/bid/invitation', [BidsInvitationsController::class, 'update']);
 Route::get('/submit/bid/{id}', [HomeController::class, 'submitBid'])->middleware('role:Provider');
 Route::get('/get/procurement/detail/{id}', [BidsInvitationsController::class, 'getProcurementDetails']);
 Route::get('/get/tender/documents/{id}', [BidsInvitationsController::class, 'getTenderDocs']);
