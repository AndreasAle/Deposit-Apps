<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\PayoutAccountController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\Admin\WithdrawalAdminController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\Admin\ReferralAdminController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\Admin\AdminForumController;
use App\Http\Controllers\SaldoController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/



Route::get('/', function () {
    return redirect('/login');
});


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (GUEST ONLY)
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| GUEST
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:3,1');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1');
});



/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/', [AdminController::class, 'index']);
    });

    
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/', [AdminController::class, 'index']);

        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::post('/users/{id}/vip', [UserController::class, 'updateVip']);
        Route::post('/users/{id}/saldo', [UserController::class, 'updateSaldo']);
    });

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/products', [AdminProductController::class, 'index']);
        Route::get('/products/create', [AdminProductController::class, 'create']);
        Route::post('/products', [AdminProductController::class, 'store']);

        Route::get('/products/{id}/edit', [AdminProductController::class, 'edit']);
        Route::post('/products/{id}/update', [AdminProductController::class, 'update']);

        Route::post('/products/{id}/toggle', [AdminProductController::class, 'toggle']);


          Route::get('/withdrawals', [WithdrawalAdminController::class, 'index']);
    Route::post('/withdrawals/{id}/approve', [WithdrawalAdminController::class, 'approve']);
    Route::post('/withdrawals/{id}/reject', [WithdrawalAdminController::class, 'reject']);
    Route::post('/withdrawals/{id}/paid', [WithdrawalAdminController::class, 'markPaid']);


      Route::view('/ui/withdrawals', 'admin.withdrawals.index');

          // admin withdraw page (UI)
    Route::view('/withdraw', 'admin.withdrawals.index')
      ->name('admin.withdraw.page');
    });

/*
|--------------------------------------------------------------------------
| USER AUTH
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {


    Route::post('/logout', [AuthController::class, 'logout']);
     Route::get('/saldo/rincian', [SaldoController::class, 'index'])->name('saldo.rincian');
       Route::get('/deposit/history', [DepositController::class, 'history'])->name('deposit.history');
});



Route::middleware('auth')->group(function () {
    Route::get('/deposit', [DepositController::class, 'index']);
    Route::post('/deposit', [DepositController::class, 'store']);
    Route::post('/deposit/callback/{order_id}', [DepositController::class, 'callback']);

     // payout accounts
  Route::get('/payout-accounts', [PayoutAccountController::class, 'index']);
  Route::post('/payout-accounts', [PayoutAccountController::class, 'store']);
  Route::put('/payout-accounts/{id}', [PayoutAccountController::class, 'update']);
  Route::delete('/payout-accounts/{id}', [PayoutAccountController::class, 'destroy']);

  // withdrawals (user)
  Route::get('/withdrawals', [WithdrawalController::class, 'index']);
  Route::get('/withdrawals/{id}', [WithdrawalController::class, 'show']);
  Route::post('/withdrawals', [WithdrawalController::class, 'store']);
  Route::post('/withdrawals/{id}/cancel', [WithdrawalController::class, 'cancel']);

    Route::view('/ui/payout-accounts', 'payout_accounts.index');
  Route::view('/ui/withdrawals', 'withdrawals.index');
   Route::get('/referral', [ReferralController::class, 'index'])->name('referral.index');

    Route::get('/team', [ForumController::class, 'index'])->name('team.index');
    Route::post('/team/posts', [ForumController::class, 'storePost'])->name('team.posts.store');

    Route::get('/team/posts/{post}', [ForumController::class, 'show'])->name('team.show');
    Route::post('/team/posts/{post}/comments', [ForumController::class, 'storeComment'])->name('team.comments.store');

    Route::delete('/team/posts/{post}', [ForumController::class, 'destroyPost'])->name('team.posts.destroy');
    Route::delete('/team/comments/{comment}', [ForumController::class, 'destroyComment'])->name('team.comments.destroy');

});


Route::middleware('auth')->post(
    '/product/buy/{id}',
    [\App\Http\Controllers\ProductBuyController::class, 'buy']
);

Route::middleware('auth')->get('/investasi', [InvestmentController::class, 'index'])
    ->name('investasi.index');

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/referral', [ReferralAdminController::class, 'index'])->name('admin.referral');
    });

    Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/forum', [AdminForumController::class, 'index'])->name('admin.forum.index');
    Route::get('/forum/posts/{post}', [AdminForumController::class, 'show'])->name('admin.forum.show');
});

Route::get('/akun', function () {
  return view('ui.akun');
})->middleware('auth');