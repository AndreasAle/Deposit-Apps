<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; // PASTIKAN IMPORT INI ADA DI PALING ATAS

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\ProductBuyController;
use App\Http\Controllers\PayoutAccountController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\SaldoController;



use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\WithdrawalAdminController;
use App\Http\Controllers\Admin\ReferralAdminController;
use App\Http\Controllers\Admin\AdminForumController;
use App\Http\Controllers\Admin\DepositAdminController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function (Request $request) {
    // 1. Ambil HANYA dari attributes (hasil filter Middleware SecurityCloaking)
    $isBot = $request->attributes->get('is_bot', false);

    if ($isBot) {
        // Berikan halaman edukasi yang sangat umum agar terlihat seperti blog informasi
        return view('landing'); 
    }
    
    // Jika Manusia
    return view('pages.tentang-rubik');
})->name('home');

// Rute umpan harus konsisten dengan halaman depan jika ingin terlihat natural
Route::get('/tentang-kami', function (Request $request) {
    $isBot = $request->attributes->get('is_bot', false);
    
    if ($isBot) {
        return view('landing'); // Bot harus melihat hal yang sama agar tidak curiga
    }

    return view('pages.tentang-rubik');
})->name('tentang.rubik');


/*
|--------------------------------------------------------------------------
| GUEST ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Referral Entry Clean URL
    |--------------------------------------------------------------------------
    | Link yang boleh dibagikan:
    | /r/KODEREFERRAL
    */
    Route::get('/r/{code}', function (Request $request, $code) {
        // Jika bot mencoba masuk lewat link referral, beri respon 404 (Bukan landing!)
        if ($request->attributes->get('is_bot') === true) {
            return response('Not Found', 404);
        }
        
        // Jika manusia, lempar ke Controller
        return app(AuthController::class)->referralEntry($request, $code);
    })->where('code', '[A-Za-z0-9]+')->name('referral.entry');

    /*
    |--------------------------------------------------------------------------
    | Legacy Register URL
    |--------------------------------------------------------------------------
    | Kalau link lama /register?ref=KODE masih tersebar,
    | jangan tampilkan form. Simpan session lalu lempar ke /undangan.
    */
    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::get('/undangan', [AuthController::class, 'showInvite'])
        ->name('invite.preview');

    Route::get('/register/form', [AuthController::class, 'showRegisterForm'])
        ->name('register.form');

    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:3,1')
        ->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});

/*
|--------------------------------------------------------------------------
| AUTH USER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Auth Action
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | Main Pages
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/pasar', [MarketController::class, 'index'])
        ->name('market.index');

    Route::get('/investasi', [InvestmentController::class, 'index'])
        ->name('investasi.index');

    Route::get('/akun', function () {
        return view('ui.akun');
    });

    /*
    |--------------------------------------------------------------------------
    | Saldo
    |--------------------------------------------------------------------------
    */

    Route::get('/saldo/rincian', [SaldoController::class, 'index'])
        ->name('saldo.rincian');

    /*
    |--------------------------------------------------------------------------
    | Deposit
    |--------------------------------------------------------------------------
    */

    Route::get('/deposit', [DepositController::class, 'index'])
        ->name('deposit.index');

    Route::post('/deposit', [DepositController::class, 'store'])
        ->name('deposit.store');

    Route::get('/deposit/history', [DepositController::class, 'history'])
        ->name('deposit.history');

    Route::get('/deposit/invoice/{id}', [DepositController::class, 'invoice'])
        ->name('deposit.invoice');

    /*
    |--------------------------------------------------------------------------
    | Product Buy / Investment Action
    |--------------------------------------------------------------------------
    */

    Route::post('/product/buy/{id}', [ProductBuyController::class, 'buy']);

    /*
    |--------------------------------------------------------------------------
    | Payout Accounts API + UI
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth')->group(function () {

        // UI halaman tambah rekening
        Route::view('/ui/payout-accounts', 'payout_accounts.index')
            ->name('payout-accounts.ui');

        // API rekening bank/e-wallet
        Route::get('/payout-accounts', [PayoutAccountController::class, 'index'])
            ->name('payout-accounts.index');

        Route::post('/payout-accounts', [PayoutAccountController::class, 'store'])
            ->name('payout-accounts.store');

        Route::put('/payout-accounts/{id}', [PayoutAccountController::class, 'update'])
            ->name('payout-accounts.update');

        Route::delete('/payout-accounts/{id}', [PayoutAccountController::class, 'destroy'])
            ->name('payout-accounts.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Withdrawals User
    |--------------------------------------------------------------------------
    */

    Route::view('/withdraw/history', 'withdrawals.history')
        ->name('withdrawals.history');

    Route::view('/ui/withdrawals', 'withdrawals.index')
        ->name('withdrawals.page');

    Route::get('/withdrawals', [WithdrawalController::class, 'index'])
        ->name('withdrawals.index');

    Route::post('/withdrawals', [WithdrawalController::class, 'store'])
        ->name('withdrawals.store');

    Route::get('/withdrawals/{id}', [WithdrawalController::class, 'show'])
        ->name('withdrawals.show');

    Route::post('/withdrawals/{id}/cancel', [WithdrawalController::class, 'cancel'])
        ->name('withdrawals.cancel');

    /*
    |--------------------------------------------------------------------------
    | Referral
    |--------------------------------------------------------------------------
    */

    Route::get('/referral', [ReferralController::class, 'index'])
        ->name('referral.index');

    /*
    |--------------------------------------------------------------------------
    | Team / Forum User
    |--------------------------------------------------------------------------
    */

    Route::get('/team', [ForumController::class, 'index'])
        ->name('team.index');

    Route::post('/team/posts', [ForumController::class, 'storePost'])
        ->name('team.posts.store');

    Route::get('/team/posts/{post}', [ForumController::class, 'show'])
        ->name('team.show');

    Route::post('/team/posts/{post}/comments', [ForumController::class, 'storeComment'])
        ->name('team.comments.store');

    Route::delete('/team/posts/{post}', [ForumController::class, 'destroyPost'])
        ->name('team.posts.destroy');

    Route::delete('/team/comments/{comment}', [ForumController::class, 'destroyComment'])
        ->name('team.comments.destroy');


        Route::middleware(['auth'])->group(function () {
    Route::view('/tentang', 'pages.tentang-rubik')->name('tentang.rubik');
});

    /*
    |--------------------------------------------------------------------------
    | UI Direct View Routes
    |--------------------------------------------------------------------------
    */




});
Route::post('/payment/jayapay/deposit/callback', [DepositController::class, 'callback'])
    ->name('payment.jayapay.deposit.callback');

Route::post('/payment/jayapay/withdrawal/callback', [WithdrawalController::class, 'jayaPayCallback'])
    ->name('payment.jayapay.withdrawal.callback');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Admin Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/', [AdminController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Admin Users
|--------------------------------------------------------------------------
*/

Route::get('/users', [UserController::class, 'index'])
    ->name('admin.users.index');

Route::get('/users/{id}', [UserController::class, 'show'])
    ->name('admin.users.show');

    Route::post('/admin/users/{id}/password', [\App\Http\Controllers\Admin\UserController::class, 'updatePassword'])
    ->name('admin.users.update-password');

Route::post('/users/{id}/vip', [UserController::class, 'updateVip'])
    ->name('admin.users.updateVip');

Route::post('/users/{id}/saldo', [UserController::class, 'updateSaldo'])
    ->name('admin.users.updateSaldo');

Route::post('/users/{id}/saldo-penarikan', [UserController::class, 'updateSaldoPenarikan'])
    ->name('admin.users.updateSaldoPenarikan');


/*
|--------------------------------------------------------------------------
| Admin Deposits
|--------------------------------------------------------------------------
*/

Route::get('/deposits', [DepositAdminController::class, 'page'])
    ->name('admin.deposits.page');

Route::get('/deposits/data', [DepositAdminController::class, 'index'])
    ->name('admin.deposits.data');

Route::post('/deposits/{id}/paid', [DepositAdminController::class, 'markPaid'])
    ->name('admin.deposits.paid');

Route::post('/deposits/{id}/failed', [DepositAdminController::class, 'markFailed'])
    ->name('admin.deposits.failed');
        /*
        |--------------------------------------------------------------------------
        | Admin Products
        |--------------------------------------------------------------------------
        */

        Route::get('/products', [AdminProductController::class, 'index']);
        Route::get('/products/create', [AdminProductController::class, 'create']);
        Route::post('/products', [AdminProductController::class, 'store']);

        Route::get('/products/{id}/edit', [AdminProductController::class, 'edit']);
        Route::post('/products/{id}/update', [AdminProductController::class, 'update']);
        Route::post('/products/{id}/toggle', [AdminProductController::class, 'toggle']);

        /*
        |--------------------------------------------------------------------------
        | Admin Withdrawals
        |--------------------------------------------------------------------------
        */

        Route::get('/withdrawals', [WithdrawalAdminController::class, 'index']);
        Route::post('/withdrawals/{id}/approve', [WithdrawalAdminController::class, 'approve']);
        Route::post('/withdrawals/{id}/reject', [WithdrawalAdminController::class, 'reject']);
        Route::post('/withdrawals/{id}/paid', [WithdrawalAdminController::class, 'markPaid']);

        Route::view('/withdraw', 'admin.withdrawals.index')
            ->name('admin.withdraw.page');

        Route::view('/ui/withdrawals', 'admin.withdrawals.index');

        /*
        |--------------------------------------------------------------------------
        | Admin Referral
        |--------------------------------------------------------------------------
        */

        Route::get('/referral', [ReferralAdminController::class, 'index'])
            ->name('admin.referral');

        /*
        |--------------------------------------------------------------------------
        | Admin Forum
        |--------------------------------------------------------------------------
        */

        Route::get('/forum', [AdminForumController::class, 'index'])
            ->name('admin.forum.index');

        Route::get('/forum/posts/{post}', [AdminForumController::class, 'show'])
            ->name('admin.forum.show');
    });