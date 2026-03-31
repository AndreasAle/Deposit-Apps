<?php

namespace App\Http\Controllers;

use App\Models\UserInvestment;

class InvestmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $investments = UserInvestment::with('product')
            ->forUser($user->id)
            ->orderByDesc('id')
            ->get();

        return view('investasi', compact('user', 'investments'));
    }
}
