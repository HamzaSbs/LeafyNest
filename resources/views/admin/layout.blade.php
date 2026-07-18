<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'LeafyNest Admin' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/browse.css') }}">
    <style>
        body { margin: 0; font-family: 'Poppins', sans-serif; background: #f8fbf5; color: #183f2d; }
        .admin-layout { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background: linear-gradient(135deg, #2f6b45, #5da96a); color: white; padding: 24px 20px; }
        .sidebar h2 { font-size: 24px; margin-bottom: 28px; }
        .sidebar a { display: block; color: white; text-decoration: none; padding: 10px 12px; border-radius: 8px; margin-bottom: 8px; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.16); }
        .main { flex: 1; padding: 28px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        h1 { margin: 0 0 8px; font-size: 26px; color: #183f2d; }
        h2 { margin: 0 0 16px; font-size: 20px; color: #183f2d; }
        .text-muted { color: #6b7b6d; }
        .card { background: white; border-radius: 14px; padding: 20px; box-shadow: 0 8px 20px rgba(24,63,45,.08); margin-bottom: 20px; }
        .cards { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; margin-bottom: 24px; }
        .card .label { color: #6b7b6d; font-size: 14px; }
        .card .value { font-size: 28px; font-weight: 700; color: #1f5b3d; margin-top: 6px; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th, .data-table td { text-align: left; padding: 10px 8px; border-bottom: 1px solid #eef5ec; vertical-align: middle; }
        .data-table th { color: #6b7b6d; font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.4px; }
        .form-inline { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
        .form-inline input { flex: 1; min-width: 160px; padding: 8px 10px; border: 1px solid #d7e7d5; border-radius: 8px; font-family: inherit; }
        .inline-form { display: inline-flex; gap: 6px; align-items: center; }
        .inline-form input { padding: 6px 8px; border: 1px solid #d7e7d5; border-radius: 6px; font-family: inherit; }
        .btn-primary { display: inline-block; padding: 10px 14px; border-radius: 8px; background: #2f6b45; color: white; text-decoration: none; border: none; cursor: pointer; font-family: inherit; }
        .btn-outline { display: inline-block; padding: 6px 10px; border-radius: 6px; background: #eef8ed; color: #2f6b45; text-decoration: none; border: none; cursor: pointer; font-family: inherit; font-size: 13px; }
        .btn-danger { display: inline-block; padding: 6px 10px; border-radius: 6px; background: #fde0e0; color: #b3261e; text-decoration: none; border: none; cursor: pointer; font-family: inherit; font-size: 13px; }
        .alert { padding: 10px 12px; border-radius: 10px; margin-bottom: 16px; font-size: 14px; }
        .alert.success { background: #e6f4ea; color: #1f5b3d; }
        .alert.error { background: #fde0e0; color: #b3261e; }
        @media (max-width: 900px) { .cards { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 640px) { .admin-layout { flex-direction: column; } .sidebar { width: 100%; } .cards { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <h2>LeafyNest</h2>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.plants.index') }}" class="{{ request()->routeIs('admin.plants.*') ? 'active' : '' }}">Manage Plants</a>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Categories</a>
            <a href="{{ route('admin.suppliers.index') }}" class="{{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">Suppliers</a>
            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">Orders</a>
            <a href="{{ route('admin.low-stock') }}" class="{{ request()->routeIs('admin.low-stock*') ? 'active' : '' }}">Low Stock Alerts</a>
            <form method="POST" action="{{ route('admin.logout') }}" style="margin-top:24px;">
                @csrf
                <button type="submit" style="width:100%; background:rgba(255,255,255,0.12); color:white; border:none; padding:10px 12px; border-radius:8px; cursor:pointer; font-family:inherit; font-weight:600;">Logout</button>
            </form>
        </aside>
        <main class="main">
            @yield('content')
        </main>
    </div>
</body>
</html>
