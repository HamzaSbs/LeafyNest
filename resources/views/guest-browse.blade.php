<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Plants</title>
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
            <a href="{{ route('register') }}" class="btn-signup">Sign up</a>
            <a href="{{ route('login') }}" class="btn-login">Log in</a>
        </div>
    </header>

    <main class="browse-page">
        <div class="container">
            <aside class="filter-sidebar card">
                <form method="GET" action="{{ route('guest.browse') }}" class="filters-form">
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
                        <a href="{{ route('guest.browse') }}" class="btn-outline">Reset</a>
                    </div>
                </form>
            </aside>

            <section class="main-area">
                <div class="main-top">
                    <a href="{{ url('/') }}" class="back-link"><strong>Back</strong></a>

                    <form method="GET" action="{{ route('guest.browse') }}" class="search-bar">
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

                <div class="guest-banner">
                    <span>👋 Browsing as a guest.</span>
                    <a href="{{ route('login') }}" class="btn-login">Log in to order</a>
                    <span class="guest-banner-sep">or</span>
                    <a href="{{ route('register') }}" class="btn-signup">Sign up</a>
                </div>

                @if(count($plants) === 0)
                    <p class="no-results">No plants found</p>
                @else
                    <div class="plants-grid">
                        @foreach($plants as $plant)
                            <article class="plant-card">
                                <div class="plant-media">
                                    <img src="{{ asset('images/' . $plant['image']) }}" alt="{{ $plant['name'] }}" class="plant-img">
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
                                    <a href="{{ route('login') }}" class="btn-primary add-cart">Log in to order</a>
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
</body>
</html>
