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
        .alert { padding: 10px 12px; border-radius: 8px; background: #eef8ed; color: #2f6b45; margin-bottom: 12px; }
    </style>
</head>
<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <h2>LeafyNest</h2>
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.plants.index') }}">Manage Plants</a>
            <a href="#">Categories</a>
            <a href="#">Suppliers</a>
            <a href="{{ route('admin.orders.index') }}">Orders</a>
            <a href="#">Low Stock Alerts</a>
        </aside>
        <main class="main">
            <h1>Admin Orders</h1>
            @if(session('success'))
                <div class="alert">{{ session('success') }}</div>
            @endif
            <div class="card">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order['order_id'] }}</td>
                                <td>{{ $order['user_name'] ?? 'Guest' }}</td>
                                <td>{{ $order['date'] }}</td>
                                <td>৳{{ number_format($order['total']) }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.orders.update-status', ['orderId' => $order['order_id']]) }}">
                                        @csrf
                                        <select name="status" onchange="this.form.submit()">
                                            <option value="Pending" {{ ($order['status'] ?? 'Pending') === 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Shipped" {{ ($order['status'] ?? 'Pending') === 'Shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="Delivered" {{ ($order['status'] ?? 'Pending') === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
