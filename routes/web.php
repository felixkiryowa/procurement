<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserAuthenticationController;
// use App\Http\Controllers\BeneficieriesController;
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

// Route::get('/manage/beneficiaries', [BeneficieriesController::class, 'index'])
// ->name('manage.beneficiaries');
// Route::post('upload', [BeneficieriesController::class, 'handleUploadingOfBeneficiaries'])
// ->name('upload.beneficiaries.file');

// Route::get('/manage/chartofaccounts', [ChartOfAccountsController::class, 'index'])
// ->name('manage.chart.of.accounts');
// Route::get('/get/ledger/categories', [ChartOfAccountsController::class, 'loadAllLedgerCategories'])
// ->name('get.ledger.categories');
// Route::get('/manage/ledger/categories', [ChartOfAccountsController::class, 'manageLedgerCategories'])
// ->name('manage.ledger.categories');
// Route::post('/create/ledger/category', [ChartOfAccountsController::class, 'createLedgerCategory'])
// ->name('create.ledger.categories');
// Route::post('/update/ledger/category', [ChartOfAccountsController::class, 'updateLedgerCategory'])
// ->name('update.ledger.categories');
// Route::get('/manage/bank/accounts', [BankAccountsController::class, 'index'])
// ->name('manage.bank.accounts');
// Route::post('/create/bank/account', [BankAccountsController::class, 'createBankAccount'])
// ->name('create.bank.account');
// Route::post('/update/bank/account', [BankAccountsController::class, 'updateBankAccount'])
// ->name('update.bank.account');
// Route::get('/manage/sub/bank/accounts', [SubBankAccountsController::class, 'index'])
// ->name('manage.sub.bank.accounts');
// Route::get('/get/bank/accounts/{id}', [SubBankAccountsController::class, 'getBankAccountsUnderGivenSelectedLedger'])
// ->name('get.bank.accounts');
// Route::get('/get/ledger/{bank_id}', [SubBankAccountsController::class, 'getLedgerWhereBankAccountExist'])
// ->name('get.ledger.with.id');
// Route::post('/create/sub/bank/account', [SubBankAccountsController::class, 'createSubBankAccount'])
// ->name('create.sub.bank.account');
// Route::post('/update/sub/bank/account', [SubBankAccountsController::class, 'updateSubBankAccount'])
// ->name('update.sub.bank.account');

// Route::get('/manage/sub/sub/bank/accounts', [SubSubAccountController::class, 'index'])
// ->name('manage.sub.sub.bank.accounts');
// Route::post('/create/sub/sub/bank/accounts', [SubSubAccountController::class,
// 'createASubSubBankAccount'])
// ->name('create.sub.sub.bank.accounts');
// Route::post('/update/sub/sub/bank/accounts', [SubSubAccountController::class,
// 'updateSubSubBankAccount'])
// ->name('update.sub.sub.bank.accounts');

// Route::get('/manage/sub/sub/sub/bank/accounts', [MoreAccountsBelowController::class, 'index'])
// ->name('manage.sub.sub.sub.bank.accounts');
// Route::post('/create/sub/sub/sub/bank/accounts', [MoreAccountsBelowController::class,
// 'createMoreAccountBelow'])
// ->name('create.sub.sub.sub.bank.accounts');
// Route::post('/update/sub/sub/sub/bank/accounts', [MoreAccountsBelowController::class,
// 'updateMoreAccountBelow'])
// ->name('update.sub.sub.sub.bank.accounts');

// Route::get('/add/general/entry', [ManageGeneralEntriesController::class, 'index'])
// ->name('add.general.entry');
// Route::post('/create/general/entry', [ManageGeneralEntriesController::class, 'createJournalEntry'])
// ->name('create.general.entry');
// Route::get('get/balance/on/account/{account_id}',
// [ManageGeneralEntriesController::class, 'calculateBalanceOnAccount'])
// ->name('get.balance.on.account');

Route::post('/user/login', [UserAuthenticationController::class, 'loginUser'])
->name('login.custom');
// Route::post('/user/logout', [UserAuthenticationController::class, 'logOutUser'])
// ->name('logout.user');

Route::post('/logout', function(){
    Auth::logout();
    return Redirect::to('/');
 })->name('logout');

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Route::get('/account/statement', [ChartOfAccountsController::class, 'AccountStatement'])
// ->name('account.statement');
// Route::get('/items/statement', [ChartOfAccountsController::class, 'ItemStatement'])
// ->name('items.statement');
// Route::get('/entire/chart/accounts', [ChartOfAccountsController::class, 'entire_chart_of_accounts'])
// ->name('entire.chart.of.accounts');
// Route::post('/account/generate/statement', [ChartOfAccountsController::class, 'generateAccountStatement'])->name('account.generateStatement');
// Route::get('/generate/trialbalance', [ChartOfAccountsController::class, 'TrialbalanceView'])
// ->name('generate.trialbalance');
// Route::post('/account/trialbalance', [ChartOfAccountsController::class, 'Trialbalance'])
// ->name('account.trialbalance');
// Route::get('/account/reports', [ChartOfAccountsController::class, 'generateAccountsReport'])
// ->name('account.reports');
// Route::get('/accounts/home', [ChartOfAccountsController::class, 'accountshome'])
// ->name('account.home');
// Route::get('/list/entries', [ChartOfAccountsController::class, 'showEntries'])
// ->name('list.account.view');

// Route::get('/list/beneficiaries', [BeneficieriesController::class, 'showBeneficiairies'])
// ->name('list.beneficiaries');
// Route::post('/create/beneficiary', [BeneficieriesController::class, 'createBeneficiairy'])
// ->name('create.beneficiaries');
// Route::post('/item/generate/statement', [ChartOfAccountsController::class, 'generateItemStatement'])->name('item.generateStatement');
// Route::get('/beneficiaries/edit/{id}', [BeneficieriesController::class, 'entryedit'])
// ->name('edit.beneficiaries');
// Route::post('/beneficiaries/update', [BeneficieriesController::class, 'entryupdate'])
// ->name('update.beneficiaries');
