<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $plant ? 'Edit Plant' : 'Add Plant' }}</title>
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
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; }
        input, select, textarea { width: 100%; padding: 10px 12px; border: 1px solid #d7e7d5; border-radius: 8px; margin-bottom: 12px; box-sizing: border-box; }
        .actions { display: flex; gap: 10px; margin-top: 12px; }
        .btn { display: inline-block; padding: 10px 14px; border-radius: 8px; text-decoration: none; border: none; cursor: pointer; }
        .btn-primary { background: #2f6b45; color: white; }
        .btn-outline { background: #eef8ed; color: #2f6b45; }
        @media (max-width: 820px) { .grid { grid-template-columns: 1fr; } }
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
            <div class="card">
                <h1>{{ $plant ? 'Edit Plant' : 'Add Plant' }}</h1>
                <form method="POST" action="{{ $plant ? route('admin.plants.update', ['id' => $plant['id']]) : route('admin.plants.store') }}">
                    @csrf
                    @if($plant)
                        @method('PUT')
                    @endif
                    <div class="grid">
                        <div>
                            <label>Name</label>
                            <input type="text" name="name" value="{{ old('name', $plant['name'] ?? '') }}" required>
                        </div>
                        <div>
                            <label>Category</label>
                            <select name="category_id" required>
                                <option value="">Select a category</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->category_id }}" @selected(old('category_id', $plant['category_id'] ?? '') == $category->category_id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label>Supplier</label>
                            <select name="supplier_id" required>
                                <option value="">Select a supplier</option>
                                @foreach($suppliers ?? [] as $supplier)
                                    <option value="{{ $supplier->supplier_id }}" @selected(old('supplier_id', $plant['supplier_id'] ?? '') == $supplier->supplier_id)>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label>Price</label>
                            <input type="number" name="price" value="{{ old('price', $plant['price'] ?? '') }}" required>
                        </div>
                        <div>
                            <label>Stock</label>
                            <input type="number" name="stock" min="0" step="1" value="{{ old('stock', $plant['stock_qty'] ?? $plant['stock'] ?? 0) }}" required>
                        </div>
                        <div>
                            <label>Sunlight</label>
                            <select name="sunlight" required>
                                <option value="">Select sunlight level</option>
                                @foreach($sunlightOptions ?? [] as $opt)
                                    <option value="{{ $opt }}" @selected(old('sunlight', $plant['sunlight'] ?? '') === $opt)>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label>Pot Size</label>
                            <select name="pot_size" required>
                                <option value="">Select pot size</option>
                                @foreach($potSizeOptions ?? [] as $opt)
                                    <option value="{{ $opt }}" @selected(old('pot_size', $plant['pot_size'] ?? '') === $opt)>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label>Season</label>
                            <select name="season" required>
                                <option value="">Select season</option>
                                @foreach($seasonOptions ?? [] as $opt)
                                    <option value="{{ $opt }}" @selected(old('season', $plant['season'] ?? '') === $opt)>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label>Care Instructions</label>
                        <textarea name="care_instructions" rows="4" required>{{ old('care_instructions', $plant['care_instructions'] ?? '') }}</textarea>
                    </div>
                    <div class="actions">
                        <button type="submit" class="btn btn-primary">Save Plant</button>
                        <a href="{{ route('admin.plants.index') }}" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
