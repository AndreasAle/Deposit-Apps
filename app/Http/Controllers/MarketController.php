<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\UserInvestment;

class MarketController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $categories = ProductCategory::with(['products' => function ($q) {
            $q->where('is_active', 1)
              ->orderBy('price', 'asc');
        }])->get();

        // Ambil investasi aktif user, mapping by product_id
        $activeInvestments = UserInvestment::where('user_id', $user->id)
            ->where('status', 'active')
            ->get()
            ->keyBy('product_id');

        $portfolioBalance = (int) (
            data_get($user, 'saldo', 0)
            + data_get($user, 'saldo_penarikan', 0)
            + data_get($user, 'total_investasi', 0)
        );

        // Total profit harian dari investasi aktif user
        $todayProfit = (int) UserInvestment::where('user_id', $user->id)
            ->where('status', 'active')
            ->sum('daily_profit');

        return view('market.index', compact(
            'user',
            'categories',
            'activeInvestments',
            'portfolioBalance',
            'todayProfit'
        ));
    }
}