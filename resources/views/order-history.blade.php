<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/browse.css') }}">
</head>
<body class="bn-bg">
    <header class="navbar navbar--browse">
        <div class="logo-container">
            <a href="{{ url('/') }}"><img src="{{ asset('images/leafyNestLogo.png') }}" alt="Leafy Nest Logo" class="logo-img"></a>
        </div>
        <div class="nav-auth">
            <a href="{{ route('dashboard') }}" class="btn-signup">Dashboard</a>
            <a href="{{ route('cart.view') }}" class="btn-login">Cart</a>
        </div>
    </header>

    <main class="order-page">
        <section class="order-shell">
            <div class="cart-heading">
                <a href="{{ route('dashboard') }}" class="back-link"><strong>Back</strong></a>
                <h1>Order History</h1>
            </div>

            @if(count($orders) === 0)
                <div class="empty-state">
                    <h2>No orders yet.</h2>
                    <p>Your session order history will appear here after checkout.</p>
                    <a href="{{ url('/plants') }}" class="btn-primary empty-action">Continue Shopping</a>
                </div>
            @else
                <div class="order-table-wrap">
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(array_reverse($orders) as $order)
                                <tr>
                                    <td>{{ $order['order_id'] }}</td>
                                    <td>{{ $order['date'] }}</td>
                                    <td>৳ {{ number_format($order['total']) }}</td>
                                    <td><span class="status-badge">{{ $order['status'] }}</span></td>
                                    <td>
                                        <a href="{{ route('order.confirmation', ['orderId' => $order['order_id']]) }}" class="btn-outline">View Details</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </main>
</body>
</html>
