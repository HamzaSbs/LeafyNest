<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders</title>
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
        .card { background: white; border-radius: 14px; padding: 20px; box-shadow: 0 8px 20px rgba(24,63,45,.08); }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #e7efe4; padding: 10px; text-align: left; }
        select { padding: 8px; border-radius: 8px; border: 1px solid #d7e7d5; }
        .btn { padding: 8px 12px; border-radius: 8px; border: none; cursor: pointer; background: #2f6b45; color: white; }
        .btn-danger { background: #b3261e; }
        .btn-danger:hover { background: #8e1f17; }
        .alert { padding: 10px 12px; border-radius: 8px; background: #eef8ed; color: #2f6b45; margin-bottom: 12px; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
        .badge-pending { background: #fff1d6; color: #b56500; }
        .badge-shipped { background: #e0eaff; color: #1f4cb0; }
        .badge-delivered { background: #eef8ed; color: #2f6b45; }
        .empty { padding: 24px; text-align: center; color: #6b7b6d; }
    </style>
</head>
<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <h2>LeafyNest</h2>
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.plants.index') }}">Manage Plants</a>
            <a href="{{ route('admin.categories.index') }}">Categories</a>
            <a href="{{ route('admin.suppliers.index') }}">Suppliers</a>
            <a href="{{ route('admin.orders.index') }}">Orders</a>
            <a href="{{ route('admin.low-stock') }}">Low Stock Alerts</a>
            <form method="POST" action="{{ route('admin.logout') }}" style="margin-top:24px;">
                @csrf
                <button type="submit" style="width:100%; background:rgba(255,255,255,0.12); color:white; border:none; padding:10px 12px; border-radius:8px; cursor:pointer; font-family:inherit; font-weight:600;">Logout</button>
            </form>
        </aside>
        <main class="main">
            <div class="page-header">
                <h1>Admin Orders</h1>
            </div>
            @if(session('success'))
                <div class="alert">{{ session('success') }}</div>
            @endif
            <div class="card">
                @if(count($orders) === 0)
                    <div class="empty">No orders have been placed yet.</div>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                @php $current = $order['status'] ?? 'Pending'; @endphp
                                <tr>
                                    <td>{{ $order['order_id'] }}</td>
                                    <td>{{ $order['user_name'] ?? 'Guest' }}</td>
                                    <td>{{ $order['date'] }}</td>
                                    <td>৳{{ number_format((float) ($order['total'] ?? 0)) }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.orders.update-status', ['orderId' => $order['order_id']]) }}" style="display:flex; gap:8px; align-items:center;">
                                            @csrf
                                            <select name="status" onchange="this.form.submit()">
                                                <option value="Pending" {{ $current === 'Pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="Shipped" {{ $current === 'Shipped' ? 'selected' : '' }}>Shipped</option>
                                                <option value="Delivered" {{ $current === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                            </select>
                                            <span class="badge badge-{{ strtolower($current) }}">{{ $current }}</span>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.orders.destroy', ['orderId' => $order['order_id']]) }}" onsubmit="return confirm('Delete this order from history?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
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
