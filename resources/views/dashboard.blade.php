<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LeafyNest Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <main class="dashboard-layout">
        <aside class="dashboard-sidebar">
            <a href="{{ url('/') }}" class="dashboard-brand">
                <img src="{{ asset('images/leafyNestLogo.png') }}" alt="LeafyNest Logo">
                <span>LeafyNest</span>
            </a>

            <nav class="dashboard-nav" aria-label="Dashboard navigation">
                <a href="{{ route('dashboard') }}" class="active">My Dashboard</a>
                <a href="{{ route('browse') }}">Browse Plants</a>
                <a href="{{ route('cart.view') }}">My Cart</a>
                <a href="{{ route('wishlist.view') }}">My Wishlist</a>
                <a href="{{ route('order.history') }}">My Orders</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </nav>
        </aside>

        <section class="dashboard-main">
            <div class="welcome-card">
                <div>
                    <span class="eyebrow">My Dashboard</span>
                    <h1>Welcome back</h1>
                    <p>Review your leafy picks, saved favorites, and recent orders from one calm little corner.</p>
                </div>
            </div>

            <div class="summary-grid">
                <article class="summary-card">
                    <span>Cart Items</span>
                    <strong>{{ $cartItemCount }}</strong>
                    <a href="{{ route('cart.view') }}">View cart</a>
                </article>

                <article class="summary-card">
                    <span>Wishlist Items</span>
                    <strong>{{ $wishlistCount }}</strong>
                    <a href="{{ route('wishlist.view') }}">View wishlist</a>
                </article>
            </div>

            <section class="orders-panel" id="recent-orders">
                <div class="panel-heading">
                    <h2>Recent Orders</h2>
                    <span>{{ count($orders) }} total</span>
                </div>

                @if(count($orders) === 0)
                    <div class="orders-empty">
                        <h3>No recent orders yet.</h3>
                        <p>Your completed orders will appear here after checkout.</p>
                    </div>
                @else
                    <div class="orders-table-wrap">
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $index => $order)
                                    <tr>
                                        <td>{{ $order['order_id'] ?? $order['order_no'] ?? $order['id'] ?? 'Order #' . ($index + 1) }}</td>
                                        <td>{{ $order['date'] ?? $order['created_at'] ?? '-' }}</td>
                                        <td><span class="status-pill">{{ $order['status'] ?? 'Pending' }}</span></td>
                                        <td>৳ {{ number_format($order['total'] ?? 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </section>
    </main>
</body>
</html>
