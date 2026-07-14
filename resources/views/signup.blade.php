<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leafy Nest - Sign Up</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Auth.css') }}">
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

    <section class="auth-section">
        <div class="auth-card">
            <h1 class="auth-title">Create Account</h1>
            <p class="auth-subtitle">Join Leafy Nest and start growing happiness</p>

            <form class="auth-form" action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="you@example.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Create a password" required>
                </div>

                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Re-enter your password" required>
                </div>

                <div class="form-extra">
                    <label class="remember-me">
                        <input type="checkbox" id="terms" name="terms" required>
                        I agree to the Terms &amp; Privacy Policy
                    </label>
                </div>

                <button type="submit" class="btn-submit">Sign Up</button>
            </form>

            <p class="auth-footer-text">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
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
                    <li><a href="browse.html">Browse Plants</a></li>
                    <li><a href="about.html">About Us</a></li>
                    <li><a href="contact.html">Contact</a></li>
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

</body>
</html>