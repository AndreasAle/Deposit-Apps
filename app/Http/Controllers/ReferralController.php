<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ReferralCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Support\ReferralCode;

class ReferralController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (empty($user->referral_code)) {
            $user->referral_code = ReferralCode::generateUnique(10);
            $user->save();
            $user->refresh();
        }

        // HITUNG TOTAL ASLI SEMUA REFERRAL
        $refCount = User::where('referred_by_user_id', $user->id)->count();

        // LIST USER REFERRAL, JANGAN LIMIT 50
        // Gunakan paginate supaya tidak berat kalau sudah ratusan/ribuan user
        $refUsers = User::where('referred_by_user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(50)
            ->withQueryString();

        $commissions = ReferralCommission::where('referrer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $totalCommission = ReferralCommission::where('referrer_id', $user->id)
            ->sum('commission_amount');

        return view('referral.index', compact(
            'user',
            'refUsers',
            'refCount',
            'commissions',
            'totalCommission'
        ));
    }
}