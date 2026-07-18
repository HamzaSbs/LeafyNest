<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .cards { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
        .card { background: white; border-radius: 14px; padding: 20px; box-shadow: 0 8px 20px rgba(24,63,45,.08); }
        .card .label { color: #6b7b6d; font-size: 14px; }
        .card .value { font-size: 28px; font-weight: 700; color: #1f5b3d; margin-top: 6px; }
        @media (max-width: 900px) { .cards { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 640px) { .admin-layout { flex-direction: column; } .sidebar { width: 100%; } .cards { grid-template-columns: 1fr; } }
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
            <div class="page-header">
                <div>
                    <p class="text-muted">Admin Panel</p>
                    <h1>Admin Dashboard</h1>
                </div>
                <a href="{{ route('plants') }}" class="btn-primary">View Storefront</a>
            </div>
            <div class="cards">
                @foreach($stats as $stat)
                    <div class="card">
                        <div class="label">{{ $stat['label'] }}</div>
                        <div class="value">{{ $stat['value'] }}</div>
                    </div>
                @endforeach
            </div>
        </main>
    </div>
</body>
</html>
