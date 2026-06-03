@php
    $user = auth()->user();

    function adminMoney($value) {
        return 'Rp ' . number_format((float) $value, 0, ',', '.');
    }

    function adminInitial($name) {
        return strtoupper(substr($name ?: 'U', 0, 1));
    }

    function investmentStatusClass($status) {
        return match ((string) $status) {
            'active' => 'is-warning',
            'finished' => 'is-success',
            default => 'is-muted',
        };
    }

    function investmentCategoryLabel($categoryId) {
        return match ((int) $categoryId) {
            1 => 'Semua',
            2 => 'Saham Velora',
            3 => 'Velora Pro',
            default => 'Unknown',
        };
    }

    function investmentCategoryNote($categoryId) {
        return in_array((int) $categoryId, [2, 3], true)
            ? 'Masuk profit harian'
            : 'Tidak masuk profit';
    }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Product Purchases | Admin</title>

    <style>
        :root {
            --bg: #f7f9fd;
            --card: #ffffff;
            --text: #101828;
            --muted: #667085;
            --line: #e7ebf3;
            --blue: #3157f8;
            --blue-soft: #eef3ff;
            --green: #12b76a;
            --green-soft: #eafaf2;
            --yellow: #f79009;
            --yellow-soft: #fff6e6;
            --red: #f04438;
            --red-soft: #fff0ee;
            --purple: #7a5af8;
            --purple-soft: #f3f0ff;
            --shadow: 0 18px 40px rgba(16, 24, 40, .06);
            --radius-xl: 28px;
            --radius-lg: 22px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif;
            background:
                radial-gradient(900px 420px at 55% -10%, rgba(49, 87, 248, .10), transparent 62%),
                var(--bg);
            color: var(--text);
        }

        a { color: inherit; text-decoration: none; }

        .page {
            max-width: 1280px;
            margin: 0 auto;
            padding: 24px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .title h1 {
            margin: 0;
            font-size: 28px;
            letter-spacing: -.04em;
        }

        .title p {
            margin: 7px 0 0;
            color: var(--muted);
            font-size: 14px;
        }

        .back-btn {
            height: 42px;
            padding: 0 16px;
            border-radius: 14px;
            background: #fff;
            border: 1px solid var(--line);
            display: inline-flex;
            align-items: center;
            font-weight: 800;
            font-size: 13px;
            box-shadow: 0 10px 24px rgba(16, 24, 40, .045);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 18px;
        }

        .stat {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            padding: 16px;
            box-shadow: 0 10px 24px rgba(16, 24, 40, .045);
        }

        .stat span {
            display: block;
            color: var(--muted);
            font-size: 12px;
            font-weight: 800;
        }

        .stat b {
            display: block;
            margin-top: 10px;
            font-size: 22px;
            letter-spacing: -.04em;
        }

        .panel {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .panel-head {
            padding: 18px;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
        }

        .panel-head b {
            display: block;
            font-size: 16px;
        }

        .panel-head span {
            display: block;
            margin-top: 4px;
            color: var(--muted);
            font-size: 13px;
        }

        .filters {
            padding: 16px 18px;
            display: grid;
            grid-template-columns: 1fr 180px 180px 110px;
            gap: 12px;
            border-bottom: 1px solid var(--line);
        }

        .filters input,
        .filters select,
        .filters button {
            height: 42px;
            border-radius: 14px;
            border: 1px solid var(--line);
            outline: none;
            padding: 0 13px;
            font-family: inherit;
            font-size: 13px;
            background: #fff;
        }

        .filters button {
            background: var(--blue);
            color: #fff;
            font-weight: 900;
            cursor: pointer;
        }

        .table-wrap {
            overflow-x: auto;
            padding: 18px;
        }

        table {
            width: 100%;
            min-width: 1050px;
            border-collapse: separate;
            border-spacing: 0;
        }

        th {
            background: #f8faff;
            color: var(--muted);
            font-size: 11.5px;
            font-weight: 900;
            text-transform: uppercase;
            text-align: left;
            padding: 13px 14px;
            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
        }

        th:first-child {
            border-left: 1px solid var(--line);
            border-radius: 15px 0 0 15px;
        }

        th:last-child {
            border-right: 1px solid var(--line);
            border-radius: 0 15px 15px 0;
        }

        td {
            padding: 15px 14px;
            border-bottom: 1px solid var(--line);
            font-size: 13px;
            vertical-align: middle;
        }

        tr:hover td {
            background: #fbfdff;
        }

        .identity {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: var(--blue-soft);
            color: var(--blue);
            font-weight: 950;
        }

        .identity b,
        .product b {
            display: block;
            font-size: 13px;
        }

        .identity span,
        .product span,
        .muted {
            display: block;
            margin-top: 4px;
            color: var(--muted);
            font-size: 12px;
        }

        .money {
            font-weight: 950;
            letter-spacing: -.02em;
        }

        .pill,
        .status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 950;
            white-space: nowrap;
        }

        .pill {
            background: var(--purple-soft);
            color: var(--purple);
            border: 1px solid rgba(122, 90, 248, .18);
        }

        .status.is-success {
            color: #027a48;
            background: var(--green-soft);
            border: 1px solid rgba(18, 183, 106, .16);
        }

        .status.is-warning {
            color: #b54708;
            background: var(--yellow-soft);
            border: 1px solid rgba(247, 144, 9, .18);
        }

        .status.is-muted {
            color: #475467;
            background: #f2f4f7;
            border: 1px solid #eaecf0;
        }

        .pagination {
            padding: 0 18px 18px;
        }

        .empty {
            padding: 34px 18px;
            text-align: center;
            color: var(--muted);
        }

        @media (max-width: 920px) {
            .page {
                padding: 14px;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .filters {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 560px) {
            .stats {
                grid-template-columns: 1fr;
            }

            .title h1 {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>
<div class="page">
    <div class="topbar">
        <div class="title">
            <h1>Product Purchases</h1>
            <p>Monitoring pembelian produk user, status investasi, dan profit yang masuk ke saldo penarikan.</p>
        </div>

        <a href="/admin" class="back-btn">← Back Dashboard</a>
    </div>

    <div class="stats">
        <div class="stat">
            <span>Total Pembelian</span>
            <b>{{ number_format($stats['total'] ?? 0) }}</b>
        </div>

        <div class="stat">
            <span>Active</span>
            <b>{{ number_format($stats['active'] ?? 0) }}</b>
        </div>

        <div class="stat">
            <span>Finished</span>
            <b>{{ number_format($stats['finished'] ?? 0) }}</b>
        </div>

        <div class="stat">
            <span>Pending Profit</span>
            <b>{{ adminMoney($stats['pending_profit'] ?? 0) }}</b>
        </div>

        <div class="stat">
            <span>Settled Profit</span>
            <b>{{ adminMoney($stats['settled_profit'] ?? 0) }}</b>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div>
                <b>Daftar Pembelian Produk</b>
                <span>Cek produk yang dibeli user dan apakah masuk profit harian atau tidak.</span>
            </div>
        </div>

        <form class="filters" method="GET" action="{{ route('admin.investments.index') }}">
            <input 
                type="text" 
                name="search" 
                value="{{ $search }}" 
                placeholder="Cari user / phone / produk / ID..."
            >

            <select name="status">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                <option value="finished" {{ $status === 'finished' ? 'selected' : '' }}>Finished</option>
            </select>

            <select name="category">
                <option value="all" {{ $category === 'all' ? 'selected' : '' }}>Semua Kategori</option>
                <option value="1" {{ $category === '1' ? 'selected' : '' }}>Semua</option>
                <option value="2" {{ $category === '2' ? 'selected' : '' }}>Saham Velora</option>
                <option value="3" {{ $category === '3' ? 'selected' : '' }}>Velora Pro</option>
            </select>

            <button type="submit">Filter</button>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Profit</th>
                    <th>Status</th>
                    <th>Start</th>
                    <th>End</th>
                </tr>
                </thead>

                <tbody>
                @forelse($investments as $investment)
                    @php
                        $categoryId = (int) data_get($investment, 'product.category_id');
                        $isProfit = in_array($categoryId, [2, 3], true);
                    @endphp

                    <tr>
                        <td>
                            <b>#{{ $investment->id }}</b>
                        </td>

                        <td>
                            <div class="identity">
                                <div class="avatar">
                                    {{ adminInitial($investment->user->name ?? 'U') }}
                                </div>
                                <div>
                                    <b>{{ $investment->user->name ?? 'Unknown User' }}</b>
                                    <span>{{ $investment->user->phone ?? 'No phone' }}</span>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="product">
                                <b>{{ $investment->product->name ?? 'Unknown Product' }}</b>
                                <span>{{ investmentCategoryNote($categoryId) }}</span>
                            </div>
                        </td>

                        <td>
                            <span class="pill">{{ investmentCategoryLabel($categoryId) }}</span>
                        </td>

                        <td>
                            <span class="money">{{ adminMoney($investment->price ?? 0) }}</span>
                        </td>

                        <td>
                            <span class="money">
                                {{ $isProfit ? adminMoney($investment->daily_profit ?? 0) : 'Rp 0' }}
                            </span>
                        </td>

                        <td>
                            <span class="status {{ investmentStatusClass($investment->status) }}">
                                {{ $investment->status }}
                            </span>
                        </td>

                        <td>
                            <span class="muted">
                                {{ optional($investment->start_date)->format('d M Y H:i') ?? '-' }}
                            </span>
                        </td>

                        <td>
                            <span class="muted">
                                {{ optional($investment->end_date)->format('d M Y H:i') ?? '-' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty">Belum ada pembelian produk.</div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $investments->links() }}
        </div>
    </div>
</div>
</body>
</html>