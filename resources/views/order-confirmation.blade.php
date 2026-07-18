<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/browse.css') }}">
</head>
<body class="bn-bg">
    <header class="navbar navbar--browse">
        <div class="logo-container">
            <a href="{{ url('/') }}"><img src="{{ asset('images/leafyNestLogo.png') }}" alt="Leafy Nest Logo" class="logo-img"></a>
        </div>
        <div class="nav-auth">
            <a href="{{ route('order.history') }}" class="btn-signup">Order History</a>
            <a href="{{ route('cart.view') }}" class="btn-login">Cart</a>
        </div>
    </header>

    <main class="order-page">
        <section class="order-shell">
            @if(!$order)
                <div class="empty-state">
                    <h2>No recent order found.</h2>
                    <p>Place an order from your cart to see the confirmation summary here.</p>
                    <a href="{{ url('/plants') }}" class="btn-primary empty-action">Continue Shopping</a>
                </div>
            @else
                <div class="confirmation-card">
                    <span class="order-kicker">Order placed</span>
                    <h1>Thank you, {{ $order['user_name'] }}.</h1>
                    <p>Your LeafyNest order is now pending and saved in your session history.</p>

                    <div class="order-meta-grid">
                        <div>
                            <span>Order ID</span>
                            <strong>{{ $order['order_id'] }}</strong>
                        </div>
                        <div>
                            <span>Date</span>
                            <strong>{{ $order['date'] }}</strong>
                        </div>
                        <div>
                            <span>Status</span>
                            <strong class="status-badge">{{ $order['status'] }}</strong>
                        </div>
                    </div>
                </div>

                <div class="order-table-wrap">
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order['items'] as $item)
                                <tr>
                                    <td>{{ $item['name'] }}</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>৳ {{ number_format($item['price']) }}</td>
                                    <td>৳ {{ number_format($item['row_total']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="order-total-card">
                    <span>Total Amount</span>
                    <strong>৳ {{ number_format($order['total']) }}</strong>
                </div>

                <a href="{{ url('/plants') }}" class="btn-primary continue-shopping">Continue Shopping</a>
            @endif
        </section>
    </main>
</body>
</html>
