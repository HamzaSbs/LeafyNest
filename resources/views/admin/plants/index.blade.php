<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Plants</title>
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
        .btn { display: inline-block; padding: 8px 12px; border-radius: 8px; text-decoration: none; margin-right: 6px; }
        .btn-primary { background: #2f6b45; color: white; }
        .btn-outline { background: #eef8ed; color: #2f6b45; }
        .alert { padding: 10px 12px; border-radius: 8px; background: #eef8ed; color: #2f6b45; margin-bottom: 12px; }
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
                <h1>Manage Plants</h1>
                <a href="{{ route('admin.plants.create') }}" class="btn btn-primary">Add Plant</a>
            </div>
            @if(session('success'))
                <div class="alert">{{ session('success') }}</div>
            @endif
            <div class="card">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plants as $plant)
                            <tr>
                                <td>{{ $plant['name'] }}</td>
                                <td>{{ $plant['category'] }}</td>
                                <td>{{ $plant['supplier'] }}</td>
                                <td>৳{{ number_format($plant['price']) }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.plants.update-stock', ['id' => $plant['id']]) }}" style="display:flex; gap:8px; align-items:center;">
                                        @csrf
                                        <input type="number" name="stock" value="{{ $plant['stock_qty'] ?? $plant['stock'] ?? 0 }}" min="0" style="width:90px; padding:6px; border-radius:6px; border:1px solid #d7e7d5;">
                                        <button type="submit" class="btn btn-outline">Update</button>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('admin.plants.edit', ['id' => $plant['id']]) }}" class="btn btn-outline">Edit</a>
                                    <form method="POST" action="{{ route('admin.plants.destroy', ['id' => $plant['id']]) }}" style="display:inline;" onsubmit="return confirm('Delete this plant?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline">Delete</button>
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
