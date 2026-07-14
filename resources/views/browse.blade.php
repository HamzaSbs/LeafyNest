<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Plants</title>
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
            <a href="{{ route('cart.view') }}" class="btn-signup">Cart</a>
            <a href="{{ route('wishlist.view') }}" class="btn-signup">Wishlist</a>
            <a href="{{ route('register') }}" class="btn-signup">Sign up</a>
            <a href="{{ route('login') }}" class="btn-login">Log in</a>
        </div>
    </header>

    <main class="browse-page">
        <div class="container">
            <aside class="filter-sidebar card">
                <form method="GET" action="{{ route('browse') }}" class="filters-form">
                    <h3 class="filters-title">Filters</h3>

                    <label class="filter-label">Category</label>
                    <select name="category" class="filter-select">
                        <option value="">Any</option>
                        @foreach($categories as $c)
                            <option value="{{ $c }}" {{ request('category')==$c? 'selected':'' }}>{{ $c }}</option>
                        @endforeach
                    </select>

                    <label class="filter-label">Sunlight</label>
                    <select name="sunlight" class="filter-select">
                        <option value="">Any</option>
                        @foreach($sunlights as $s)
                            <option value="{{ $s }}" {{ request('sunlight')==$s? 'selected':'' }}>{{ $s }}</option>
                        @endforeach
                    </select>

                    <label class="filter-label">Pot Size</label>
                    <select name="pot_size" class="filter-select">
                        <option value="">Any</option>
                        @foreach($potSizes as $p)
                            <option value="{{ $p }}" {{ request('pot_size')==$p? 'selected':'' }}>{{ $p }}</option>
                        @endforeach
                    </select>

                    <label class="filter-label">Season</label>
                    <select name="season" class="filter-select">
                        <option value="">Any</option>
                        @foreach($seasons as $se)
                            <option value="{{ $se }}" {{ request('season')==$se? 'selected':'' }}>{{ $se }}</option>
                        @endforeach
                    </select>

                    <label class="filter-label">Min Price (৳)</label>
                    <input type="number" name="min_price" value="{{ request('min_price') }}" class="filter-input">

                    <label class="filter-label">Max Price (৳)</label>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" class="filter-input">

                    <input type="hidden" name="search" value="{{ request('search') }}">

                    <div class="filter-actions">
                        <button type="submit" class="btn-primary">Apply</button>
                        <a href="{{ route('browse') }}" class="btn-outline">Reset</a>
                    </div>
                </form>
            </aside>

            <section class="main-area">
                <div class="main-top">
                    <a href="{{ url('/') }}" class="back-link"><strong>Back</strong></a>

                    <form method="GET" action="{{ route('browse') }}" class="search-bar">
                        {{-- preserve filters when searching --}}
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        <input type="hidden" name="sunlight" value="{{ request('sunlight') }}">
                        <input type="hidden" name="pot_size" value="{{ request('pot_size') }}">
                        <input type="hidden" name="season" value="{{ request('season') }}">
                        <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                        <input type="hidden" name="max_price" value="{{ request('max_price') }}">

                        <div class="search-input-wrap">
                            <span class="search-icon">🔍</span>
                            <input type="text" name="search" placeholder="Search plants by name..." value="{{ request('search') }}" class="search-input">
                            <button type="submit" class="btn-primary btn-search">Search</button>
                        </div>
                    </form>
                </div>

                @if(count($plants) === 0)
                    <p class="no-results">No plants found</p>
                @else
                    <div class="plants-grid">
                        @foreach($plants as $plant)
                            <article class="plant-card">
                                <div class="plant-media">
                                    <img src="{{ asset('images/' . $plant['image']) }}" alt="{{ $plant['name'] }}" class="plant-img">
                                    <button
                                        type="button"
                                        class="wishlist-btn {{ in_array($plant['id'], $wishlist ?? [], true) ? 'is-filled' : '' }}"
                                        data-plant-id="{{ $plant['id'] }}"
                                        aria-label="Toggle {{ $plant['name'] }} wishlist"
                                        aria-pressed="{{ in_array($plant['id'], $wishlist ?? [], true) ? 'true' : 'false' }}"
                                    >&hearts;</button>
                                </div>

                                <div class="plant-body">
                                    <span class="plant-category">{{ $plant['category'] }}</span>
                                    <h3 class="plant-name">{{ $plant['name'] }}</h3>

                                    @if($plant['stock_qty'] > 5)
                                        <span class="stock-badge in-stock">In Stock</span>
                                    @elseif($plant['stock_qty'] > 0)
                                        <span class="stock-badge low-stock">Low Stock</span>
                                    @else
                                        <span class="stock-badge out-of-stock">Out of Stock</span>
                                    @endif

                                    <div class="price-row">
                                        <div class="price">৳ {{ number_format($plant['price']) }}</div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="button" class="btn-primary add-cart" data-plant-id="{{ $plant['id'] }}">Add to Cart</button>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </main>

    <footer class="site-footer">
        <div class="site-footer-bottom">
            <p class="footer-copyright">© 2026 LeafyNest. All rights reserved.</p>
        </div>
    </footer>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.querySelectorAll('.add-cart').forEach((button) => {
            button.addEventListener('click', async () => {
                const response = await fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        plant_id: Number(button.dataset.plantId),
                        quantity: 1,
                    }),
                });

                if (response.ok) {
                    button.textContent = 'Added';
                    setTimeout(() => {
                        button.textContent = 'Add to Cart';
                    }, 1200);
                }
            });
        });

        document.querySelectorAll('.wishlist-btn').forEach((button) => {
            button.addEventListener('click', async () => {
                const response = await fetch('{{ route('wishlist.toggle') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        plant_id: Number(button.dataset.plantId),
                    }),
                });

                if (response.ok) {
                    const data = await response.json();
                    button.classList.toggle('is-filled', data.wishlisted);
                    button.setAttribute('aria-pressed', data.wishlisted ? 'true' : 'false');
                }
            });
        });
    </script>
</body>
</html>
