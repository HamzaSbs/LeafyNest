<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leafy Nest - Nurture Nature</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=K2D:wght@800&display=swap" rel="stylesheet">
</head>
<body>

    <header class="navbar">
        <div class="logo-container">
            <img src="{{ asset('images/leafyNestLogo.png') }}" alt="Leafy Nest Logo" class="logo-img">
        </div>
        <div class="nav-auth">
            <a href="{{ route('register') }}" class="btn-signup">Sign up</a>
            <a href="{{ route('login') }}" class="btn-login">Log in</a>
        </div>
    </header>

    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">
                <span class="text-green">NURTURE NATURE</span>
                <span class="text-gradient">GROW HAPPINESS</span>
            </h1>
            <a href="#best-sellers" class="btn-explore">Explore Plants</a>
        </div>
        <div class="hero-bg-banner" style="background-image: url('{{ asset('images/BelowHeroNurseryPic.png') }}');"></div>
    </section>

    <section class="categories-section">
        <h2 class="categories-title">Shop by Category</h2>

        <div class="categories-grid">
            <a href="#" class="category-card">
                <img src="{{ asset('images/indoor_plants.jpg') }}" alt="Indoor Plants" class="category-image">
                <span class="category-label">Indoor Plants</span>
            </a>

            <a href="#" class="category-card">
                <img src="{{ asset('images/outdoor_plant.jpg') }}" alt="Outdoor Plants" class="category-image">
                <span class="category-label">Outdoor Plants</span>
            </a>

            <a href="#" class="category-card">
                <img src="{{ asset('images/succulents.jpeg') }}" alt="Succulents" class="category-image">
                <span class="category-label">Succulents</span>
            </a>

            <a href="#" class="category-card">
                <img src="{{ asset('images/flowering_plants.jpg') }}" alt="Flowering Plants" class="category-image">
                <span class="category-label">Flowering Plants</span>
            </a>

            <a href="#" class="category-card">
                <img src="{{ asset('images/tree.png') }}" alt="Trees" class="category-image">
                <span class="category-label">Trees</span>
            </a>

            <a href="#" class="category-card">
                <img src="{{ asset('images/herbs.jpg') }}" alt="Herbs" class="category-image">
                <span class="category-label">Herbs</span>
            </a>
        </div>
    </section>

    <section class="featured-plants-section" id="best-sellers">
        <h2 class="featured-plants-title">Our Best Sellers</h2>
        <p class="featured-plants-subtitle">Handpicked favourites from our nursery</p>

        <div class="featured-plants-grid">
            <div class="plant-card">
                <div class="plant-card-top">
                    <img src="{{ asset('images/MOnstera Deli.jpg') }}" alt="Monstera Deliciosa" class="plant-img">
                    <button type="button" class="wishlist-btn {{ in_array(1, $wishlist ?? [], true) ? 'is-filled' : '' }}" data-plant-id="1" aria-label="Toggle Monstera Deliciosa wishlist" aria-pressed="{{ in_array(1, $wishlist ?? [], true) ? 'true' : 'false' }}">&hearts;</button>
                </div>
                <span class="plant-category-tag">Indoor</span>
                <h3 class="plant-name">Monstera Deliciosa</h3>
                <span class="stock-badge in-stock">In Stock</span>
                <div class="plant-price-row">
                    <span class="plant-price">৳ 850</span>
                    <button type="button" class="add-to-cart-btn" data-plant-id="1">Add to Cart</button>
                </div>
            </div>

            <div class="plant-card">
                <div class="plant-card-top">
                    <img src="{{ asset('images/echeveria succul.jpg') }}" alt="Echeveria Succulent" class="plant-img">
                    <button type="button" class="wishlist-btn {{ in_array(2, $wishlist ?? [], true) ? 'is-filled' : '' }}" data-plant-id="2" aria-label="Toggle Echeveria Succulent wishlist" aria-pressed="{{ in_array(2, $wishlist ?? [], true) ? 'true' : 'false' }}">&hearts;</button>
                </div>
                <span class="plant-category-tag">Succulent</span>
                <h3 class="plant-name">Echeveria Succulent</h3>
                <span class="stock-badge low-stock">Low Stock</span>
                <div class="plant-price-row">
                    <span class="plant-price">৳ 320</span>
                    <button type="button" class="add-to-cart-btn" data-plant-id="2">Add to Cart</button>
                </div>
            </div>

            <div class="plant-card">
                <div class="plant-card-top">
                    <img src="{{ asset('images/peace-lily.jpg') }}" alt="Peace Lily" class="plant-img">
                    <button type="button" class="wishlist-btn {{ in_array(3, $wishlist ?? [], true) ? 'is-filled' : '' }}" data-plant-id="3" aria-label="Toggle Peace Lily wishlist" aria-pressed="{{ in_array(3, $wishlist ?? [], true) ? 'true' : 'false' }}">&hearts;</button>
                </div>
                <span class="plant-category-tag">Indoor</span>
                <h3 class="plant-name">Peace Lily</h3>
                <span class="stock-badge in-stock">In Stock</span>
                <div class="plant-price-row">
                    <span class="plant-price">৳ 490</span>
                    <button type="button" class="add-to-cart-btn" data-plant-id="3">Add to Cart</button>
                </div>
            </div>

            <div class="plant-card">
                <div class="plant-card-top">
                    <img src="{{ asset('images/fiddle leaf.jpg') }}" alt="Fiddle Leaf Fig" class="plant-img">
                    <button type="button" class="wishlist-btn {{ in_array(4, $wishlist ?? [], true) ? 'is-filled' : '' }}" data-plant-id="4" aria-label="Toggle Fiddle Leaf Fig wishlist" aria-pressed="{{ in_array(4, $wishlist ?? [], true) ? 'true' : 'false' }}">&hearts;</button>
                </div>
                <span class="plant-category-tag">Outdoor</span>
                <h3 class="plant-name">Fiddle Leaf Fig</h3>
                <span class="stock-badge in-stock">In Stock</span>
                <div class="plant-price-row">
                    <span class="plant-price">৳ 1,200</span>
                    <button type="button" class="add-to-cart-btn" data-plant-id="4">Add to Cart</button>
                </div>
            </div>
        </div>

        <a href="{{ route('guest.browse') }}" class="view-all-plants-btn">View All Plants →</a>
    </section>

    <section class="why-choose-us-section">
        <h2 class="why-choose-us-title">Why LeafyNest?</h2>
        <p class="why-choose-us-subtitle">More than just plants — a complete growing experience</p>

        <div class="why-choose-us-grid">
            <div class="feature-card">
                <div class="feature-icon">🌱</div>
                <h3 class="feature-title">Healthy & Verified Plants</h3>
                <p class="feature-description">Every plant is quality-checked and carefully nurtured before it reaches you</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🚚</div>
                <h3 class="feature-title">Fast Doorstep Delivery</h3>
                <p class="feature-description">Fresh plants packaged safely and delivered right to your door</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">💧</div>
                <h3 class="feature-title">Expert Care Instructions</h3>
                <p class="feature-description">Detailed care guides included with every plant so your greens always thrive</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🔔</div>
                <h3 class="feature-title">Real-Time Stock Tracking</h3>
                <p class="feature-description">Live inventory updates so you always know what's available before you order</p>
            </div>
        </div>
    </section>

    <footer class="site-footer">
        <div class="site-footer-top">
            <div class="footer-brand-column">
                <p class="footer-tagline">Nurture Nature. Grow Happiness.</p>
                <p class="footer-description">Your trusted online nursery for healthy plants, expert care tips, and doorstep delivery.</p>
            </div>

            <div class="footer-links-column">
                <h3 class="footer-column-title">Quick Links</h3>
                <ul class="footer-link-list">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ route('guest.browse') }}">Browse Plants</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>

            <div class="footer-links-column">
                <h3 class="footer-column-title">Categories</h3>
                <ul class="footer-link-list">
                    <li><a href="#">Indoor Plants</a></li>
                    <li><a href="#">Outdoor Plants</a></li>
                    <li><a href="#">Succulents</a></li>
                    <li><a href="#">Flowering Plants</a></li>
                    <li><a href="#">Trees</a></li>
                    <li><a href="#">Herbs</a></li>
                </ul>
            </div>

            <div class="footer-contact-column">
                <h3 class="footer-column-title">Get In Touch</h3>
                <p class="footer-contact-item">📍 Dhaka, Bangladesh</p>
                <p class="footer-contact-item">📧 hello@leafynest.com</p>
                <p class="footer-contact-item">📞 +880 1700-000000</p>

                <div class="footer-socials">
                    <a href="#" aria-label="Facebook">f</a>
                    <a href="#" aria-label="Instagram">in</a>
                    <a href="#" aria-label="Twitter X">x</a>
                </div>
            </div>
        </div>

        <hr class="footer-divider">

        <div class="site-footer-bottom">
            <p class="footer-copyright">© 2026 LeafyNest. All rights reserved.</p>
            <div class="footer-bottom-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </footer>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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

        document.querySelectorAll('.add-to-cart-btn').forEach((button) => {
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
    </script>
</body>
</html>
