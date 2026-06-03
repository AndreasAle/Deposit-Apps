<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserInvestment;
use Illuminate\Http\Request;

class InvestmentAdminController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $status = $request->query('status', 'all');
        $category = $request->query('category', 'all');

        $query = UserInvestment::query()
            ->with([
                'user:id,name,phone',
                'product:id,name,category_id,price,daily_profit,total_profit,duration_days',
            ])
            ->latest();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->orWhereHas('product', function ($p) use ($search) {
                        $p->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if (in_array($status, ['active', 'finished'], true)) {
            $query->where('status', $status);
        }

        if (in_array($category, ['1', '2', '3'], true)) {
            $query->whereHas('product', function ($q) use ($category) {
                $q->where('category_id', (int) $category);
            });
        }

        $investments = $query
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total'           => UserInvestment::count(),
            'active'          => UserInvestment::where('status', 'active')->count(),
            'finished'        => UserInvestment::where('status', 'finished')->count(),
            'pending_profit'  => UserInvestment::where('status', 'active')->sum('total_profit'),
            'settled_profit'  => UserInvestment::where('status', 'finished')->sum('total_profit'),
        ];

        return view('admin.investments.index', compact(
            'investments',
            'stats',
            'search',
            'status',
            'category'
        ));
    }
}