<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Wishlist</title>
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
            <a href="{{ route('cart.view') }}" class="btn-signup">Cart</a>
        </div>
    </header>

    <main class="browse-page">
        <section class="cart-shell">
            <div class="cart-heading">
                <a href="{{ route('browse') }}" class="back-link"><strong>Back</strong></a>
                <h1>Your Wishlist</h1>
            </div>

            @if(count($plants) === 0)
                <div class="empty-state">
                    <h2>No favorites yet.</h2>
                    <p>Tap the heart on a plant card to save it here.</p>
                    <a href="{{ route('browse') }}" class="btn-primary empty-action">Browse Plants</a>
                </div>
            @else
                <div class="plants-grid">
                    @foreach($plants as $plant)
                        <article class="plant-card">
                            <div class="plant-media">
                                <img src="{{ asset('images/' . $plant['image']) }}" alt="{{ $plant['name'] }}" class="plant-img">
                                <span class="wishlist-btn is-filled" aria-label="{{ $plant['name'] }} is in wishlist">&hearts;</span>
                            </div>
                            <div class="plant-body">
                                <span class="plant-category">{{ $plant['category'] }}</span>
                                <h3 class="plant-name">{{ $plant['name'] }}</h3>
                                <div class="price-row">
                                    <div class="price">৳ {{ number_format($plant['price']) }}</div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </main>
</body>
</html>
