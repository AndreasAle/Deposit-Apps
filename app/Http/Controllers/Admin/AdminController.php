<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Deposit;
use App\Models\Withdrawal;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'       => User::where('role', 'user')->count(),
            'new_users_month'   => User::where('role', 'user')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),

            'total_products'    => Product::count(),
            'active_products'   => Product::where('is_active', 1)->count(),

            'deposit_today'     => Deposit::whereDate('created_at', today())->sum('amount'),
            'deposit_paid'      => Deposit::where('status', 'PAID')->sum('amount'),
            'deposit_unpaid'    => Deposit::where('status', 'UNPAID')->count(),

            'withdraw_pending'  => Withdrawal::where('status', 'PENDING')->count(),
            'withdraw_paid'     => Withdrawal::where('status', 'PAID')->count(),
        ];

        $latestDeposits = Deposit::with('user:id,name,phone')
            ->latest()
            ->limit(6)
            ->get();

        $latestWithdrawals = Withdrawal::with('user:id,name,phone')
            ->latest()
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'latestDeposits',
            'latestWithdrawals'
        ));
    }
}