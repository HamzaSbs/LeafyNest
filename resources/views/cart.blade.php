<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/browse.css') }}">
</head>
<body class="bn-bg">
    <header class="navbar navbar--browse">
        <div class="logo-container">
            <a href="{{ url('/') }}"><img src="{{ asset('images/leafyNestLogo.png') }}" alt="Leafy Nest Logo" class="logo-img"></a>
        </div>
        <div class="nav-auth">
            <a href="{{ route('browse') }}" class="btn-signup">Browse</a>
            <a href="{{ route('wishlist.view') }}" class="btn-signup">Wishlist</a>
            <a href="{{ route('dashboard') }}" class="btn-login">My Dashboard</a>
        </div>
    </header>

    <main class="cart-page">
        <section class="cart-shell">
            <div class="cart-heading">
                <h1>Your Cart</h1>
            </div>

            @if(count($cartItems) === 0)
                <div class="empty-state">
                    <h2>Your cart is waiting for something leafy.</h2>
                    <p>Add a plant you love, then come back here to review your order.</p>
                    <a href="{{ route('browse') }}" class="btn-primary empty-action">Browse Plants</a>
                </div>
            @else
                <div class="cart-table-wrap">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Plant</th>
                                <th>Unit Price</th>
                                <th>Quantity</th>
                                <th>Row Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                                <tr data-cart-row="{{ $item['id'] }}">
                                    <td>
                                        <div class="cart-plant">
                                            <img src="{{ asset('images/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                            <span>{{ $item['name'] }}</span>
                                        </div>
                                    </td>
                                    <td>৳ {{ number_format($item['price']) }}</td>
                                    <td>
                                        <input
                                            type="number"
                                            min="1"
                                            value="{{ $item['quantity'] }}"
                                            class="cart-qty"
                                            data-plant-id="{{ $item['id'] }}"
                                        >
                                    </td>
                                    <td class="row-total">৳ {{ number_format($item['row_total']) }}</td>
                                    <td>
                                        <button type="button" class="btn-outline remove-cart" data-plant-id="{{ $item['id'] }}">Remove</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <aside class="order-summary">
                    <div>
                        <span>Subtotal</span>
                        <strong id="cart-subtotal">৳ {{ number_format($subtotal) }}</strong>
                    </div>
                    <div>
                        <span>Total</span>
                        <strong id="cart-total">৳ {{ number_format($total) }}</strong>
                    </div>
                    <form method="POST" action="{{ route('order.place') }}" class="order-form" id="order-form">
                        @csrf
                        <div class="order-field">
                            <label for="phone">Mobile Number</label>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                placeholder="e.g. 017XXXXXXXX"
                                inputmode="numeric"
                                pattern="[0-9]{10,15}"
                                autocomplete="tel"
                                required
                            >
                        </div>
                        <div class="order-field">
                            <label for="address">Delivery Address</label>
                            <textarea
                                id="address"
                                name="address"
                                rows="3"
                                placeholder="House, Road, City"
                                minlength="5"
                                required
                            ></textarea>
                        </div>
                        <button type="submit" class="btn-primary proceed-order" id="proceed-order" disabled aria-disabled="true">
                            Proceed to Order
                        </button>
                    </form>
                </aside>
            @endif
        </section>
    </main>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const formatMoney = (value) => `৳ ${Number(value).toLocaleString()}`;

        function refreshTotals(cart) {
            document.getElementById('cart-subtotal').textContent = formatMoney(cart.subtotal);
            document.getElementById('cart-total').textContent = formatMoney(cart.total);

            cart.items.forEach((item) => {
                const row = document.querySelector(`[data-cart-row="${item.id}"]`);
                if (row) {
                    row.querySelector('.row-total').textContent = formatMoney(item.row_total);
                }
            });
        }

        document.querySelectorAll('.cart-qty').forEach((input) => {
            input.addEventListener('change', async () => {
                const quantity = Math.max(1, Number(input.value || 1));
                input.value = quantity;

                const response = await fetch(`/cart/${input.dataset.plantId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ quantity }),
                });

                if (response.ok) {
                    const data = await response.json();
                    refreshTotals(data.cart);
                }
            });
        });

        const orderForm = document.getElementById('order-form');
        if (orderForm) {
            const proceedBtn = document.getElementById('proceed-order');
            const phoneInput = document.getElementById('phone');
            const addressInput = document.getElementById('address');

            const toggleProceed = () => {
                const phone = phoneInput.value.trim();
                const address = addressInput.value.trim();
                const phoneValid = /^[0-9]{10,15}$/.test(phone);
                const addressValid = address.length >= 5;
                const ready = phoneValid && addressValid;
                proceedBtn.disabled = !ready;
                proceedBtn.setAttribute('aria-disabled', String(!ready));
                proceedBtn.classList.toggle('is-ready', ready);
            };

            phoneInput.addEventListener('input', toggleProceed);
            addressInput.addEventListener('input', toggleProceed);
            orderForm.addEventListener('submit', (e) => {
                if (proceedBtn.disabled) {
                    e.preventDefault();
                }
            });
            toggleProceed();
        }

        document.querySelectorAll('.remove-cart').forEach((button) => {
            button.addEventListener('click', async () => {
                const response = await fetch(`/cart/${button.dataset.plantId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });

                if (response.ok) {
                    const row = document.querySelector(`[data-cart-row="${button.dataset.plantId}"]`);
                    if (row) {
                        row.remove();
                    }

                    const data = await response.json();
                    if (data.cart.items.length === 0) {
                        window.location.reload();
                    } else {
                        refreshTotals(data.cart);
                    }
                }
            });
        });
    </script>
</body>
</html>
