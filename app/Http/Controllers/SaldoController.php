<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaldoController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        // filter opsional: all | deposit | investment
        $type = $request->query('type', 'all');
        if (!in_array($type, ['all', 'deposit', 'investment'], true)) {
            $type = 'all';
        }

        // deposits (uang masuk)
        $deposits = DB::table('deposits')
            ->where('user_id', $userId)
            ->where('status', 'PAID')
            ->selectRaw("
                created_at as happened_at,
                'deposit' as activity_type,
                amount as amount,
                method as method,
                order_id as ref,
                NULL as product_name,
                status as status
            ");

        // user_investments (uang keluar / beli investasi)
        $investments = DB::table('user_investments')
            ->leftJoin('products', 'products.id', '=', 'user_investments.product_id')
            ->where('user_investments.user_id', $userId)
            ->selectRaw("
                user_investments.created_at as happened_at,
                'investment' as activity_type,
                user_investments.price as amount,
                NULL as method,
                user_investments.id as ref,
                products.name as product_name,
                COALESCE(user_investments.status, 'active') as status
            ");

        // gabungkan + urutkan
        $union = $deposits->unionAll($investments);

        $base = DB::query()->fromSub($union, 'x');

        if ($type !== 'all') {
            $base->where('activity_type', $type);
        }

        $activities = $base
            ->orderByDesc('happened_at')
            ->paginate(20)
            ->withQueryString();

        return view('saldo.rincian', compact('activities', 'type'));
    }
}
