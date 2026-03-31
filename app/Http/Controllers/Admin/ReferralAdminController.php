<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ReferralCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferralAdminController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'overview'); // overview | users | commissions

        // ====== OVERVIEW ======
        $totalUsers = User::where('role', 'user')->count();
        $totalReferredUsers = User::whereNotNull('referred_by_user_id')->count();

        $totalCommission = (float) ReferralCommission::sum('commission_amount');
        $commissionToday  = (float) ReferralCommission::whereDate('created_at', today())->sum('commission_amount');
        $commission7d     = (float) ReferralCommission::where('created_at', '>=', now()->subDays(7))->sum('commission_amount');

        $breakdown = ReferralCommission::select('source_type', DB::raw('SUM(commission_amount) as total'))
            ->groupBy('source_type')
            ->pluck('total', 'source_type')
            ->toArray();

        $topReferrers = ReferralCommission::query()
            ->join('users as u', 'u.id', '=', 'referral_commissions.referrer_id')
            ->select(
                'referral_commissions.referrer_id',
                'u.name as referrer_name',
                'u.phone as referrer_phone',
                DB::raw('COUNT(referral_commissions.id) as total_trx'),
                DB::raw('SUM(referral_commissions.commission_amount) as total_commission')
            )
            ->groupBy('referral_commissions.referrer_id', 'u.name', 'u.phone')
            ->orderByDesc('total_commission')
            ->limit(10)
            ->get();

        // ====== USERS TAB (referrer list + optional detail) ======
        $referrers = User::query()
            ->leftJoin('users as ru', 'ru.referred_by_user_id', '=', 'users.id')
            ->where('users.role', 'user')
            ->groupBy('users.id', 'users.name', 'users.phone', 'users.referral_code', 'users.referral_earned_total', 'users.created_at')
            ->select(
                'users.id',
                'users.name',
                'users.phone',
                'users.referral_code',
                'users.referral_earned_total',
                'users.created_at',
                DB::raw('COUNT(ru.id) as referrals_count')
            )
            ->orderByDesc('referrals_count')
            ->paginate(15)
            ->withQueryString();

        $referrerId = $request->integer('referrer_id');
        $referrerDetail = null;
        $referredUsers = collect();
        $referrerCommissions = collect();

        if ($referrerId) {
            $referrerDetail = User::where('id', $referrerId)->where('role', 'user')->first();

            if ($referrerDetail) {
                $referredUsers = User::where('referred_by_user_id', $referrerDetail->id)
                    ->orderByDesc('created_at')
                    ->limit(100)
                    ->get(['id', 'name', 'phone', 'created_at']);

                $referrerCommissions = ReferralCommission::query()
                    ->leftJoin('users as r', 'r.id', '=', 'referral_commissions.referred_user_id')
                    ->where('referral_commissions.referrer_id', $referrerDetail->id)
                    ->orderByDesc('referral_commissions.created_at')
                    ->limit(50)
                    ->get([
                        'referral_commissions.*',
                        'r.name as referred_name',
                        'r.phone as referred_phone'
                    ]);
            }
        }

        // ====== COMMISSIONS TAB (filterable) ======
        $cq = ReferralCommission::query()
            ->leftJoin('users as ref', 'ref.id', '=', 'referral_commissions.referrer_id')
            ->leftJoin('users as rd', 'rd.id', '=', 'referral_commissions.referred_user_id')
            ->select(
                'referral_commissions.*',
                'ref.name as referrer_name',
                'ref.phone as referrer_phone',
                'rd.name as referred_name',
                'rd.phone as referred_phone'
            )
            ->orderByDesc('referral_commissions.created_at');

        if ($request->filled('source_type')) {
            $cq->where('referral_commissions.source_type', $request->string('source_type'));
        }
        if ($request->filled('referrer_id')) {
            $cq->where('referral_commissions.referrer_id', (int) $request->referrer_id);
        }
        if ($request->filled('referred_user_id')) {
            $cq->where('referral_commissions.referred_user_id', (int) $request->referred_user_id);
        }
        if ($request->filled('date_from')) {
            $cq->whereDate('referral_commissions.created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $cq->whereDate('referral_commissions.created_at', '<=', $request->date_to);
        }

        $commissions = $cq->paginate(20)->withQueryString();

        return view('admin.referral.index', compact(
            'tab',
            'totalUsers',
            'totalReferredUsers',
            'totalCommission',
            'commissionToday',
            'commission7d',
            'breakdown',
            'topReferrers',
            'referrers',
            'referrerDetail',
            'referredUsers',
            'referrerCommissions',
            'commissions'
        ));
    }
}
