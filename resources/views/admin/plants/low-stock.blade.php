<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low Stock Alerts</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/browse.css') }}">
    <style>
        body { margin: 0; font-family: 'Poppins', sans-serif; background: #f8fbf5; color: #183f2d; }
        .admin-layout { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background: linear-gradient(135deg, #2f6b45, #5da96a); color: white; padding: 24px 20px; }
        .sidebar h2 { font-size: 24px; margin-bottom: 28px; }
        .sidebar a { display: block; color: white; text-decoration: none; padding: 10px 12px; border-radius: 8px; margin-bottom: 8px; }
        .sidebar a:hover { background: rgba(255,255,255,0.16); }
        .main { flex: 1; padding: 28px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .card { background: white; border-radius: 14px; padding: 20px; box-shadow: 0 8px 20px rgba(24,63,45,.08); }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border-bottom: 1px solid #e7efe4; padding: 10px; text-align: left; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
        .badge-low { background: #fff1d6; color: #b56500; }
        .badge-out { background: #fde0e0; color: #b3261e; }
        .badge-ok { background: #eef8ed; color: #2f6b45; }
        .btn { display: inline-block; padding: 8px 12px; border-radius: 8px; text-decoration: none; }
        .btn-outline { background: #eef8ed; color: #2f6b45; }
        .alert { padding: 10px 12px; border-radius: 8px; background: #eef8ed; color: #2f6b45; margin-bottom: 12px; }
        .empty { padding: 24px; text-align: center; color: #6b7b6d; }
    </style>
</head>
<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <h2>LeafyNest</h2>
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.plants.index') }}">Manage Plants</a>
            <a href="{{ route('plants') }}">Categories</a>
            <a href="{{ route('admin.plants.index') }}">Suppliers</a>
            <a href="{{ route('admin.orders.index') }}">Orders</a>
            <a href="{{ route('admin.low-stock') }}">Low Stock Alerts</a>
            <form method="POST" action="{{ route('admin.logout') }}" style="margin-top:24px;">
                @csrf
                <button type="submit" style="width:100%; background:rgba(255,255,255,0.12); color:white; border:none; padding:10px 12px; border-radius:8px; cursor:pointer; font-family:inherit; font-weight:600;">Logout</button>
            </form>
        </aside>
        <main class="main">
            <div class="page-header">
                <div>
                    <h1>Low Stock Alerts</h1>
                    <p style="margin:4px 0 0; color:#6b7b6d;">Plants with {{ $threshold }} or fewer items in stock.</p>
                </div>
                <a href="{{ route('admin.plants.index') }}" class="btn btn-outline">Back to Plants</a>
            </div>

            @if(session('success'))
                <div class="alert">{{ session('success') }}</div>
            @endif

            <div class="card">
                @if(count($plants) === 0)
                    <div class="empty">All plants are well stocked. Nothing to worry about.</div>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Supplier</th>
                                <th>Price</th>
                                <th>In Stock</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plants as $plant)
                                @php $qty = (int)($plant['stock_qty'] ?? 0); @endphp
                                <tr>
                                    <td>{{ $plant['name'] }}</td>
                                    <td>{{ $plant['category'] }}</td>
                                    <td>{{ $plant['supplier'] }}</td>
                                    <td>৳{{ number_format($plant['price']) }}</td>
                                    <td><strong>{{ $qty }}</strong></td>
                                    <td>
                                        @if($qty === 0)
                                            <span class="badge badge-out">Out of stock</span>
                                        @else
                                            <span class="badge badge-low">Low stock</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.plants.edit', ['id' => $plant['id']]) }}" class="btn btn-outline">Update Stock</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </main>
    </div>
</body>
</html>