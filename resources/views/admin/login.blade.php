<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LeafyNest - Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/browse.css') }}">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1f4f33, #5da96a);
            color: #183f2d;
            padding: 24px;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: 18px;
            padding: 36px 32px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
        }
        .brand {
            text-align: center;
            margin-bottom: 24px;
        }
        .brand .leaf {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: linear-gradient(135deg, #2f6b45, #5da96a);
            color: white;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .brand h1 {
            margin: 0;
            font-size: 24px;
            color: #183f2d;
        }
        .brand p {
            margin: 4px 0 0;
            color: #6b7b6d;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            font-size: 14px;
            color: #183f2d;
        }
        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d7e7d5;
            border-radius: 10px;
            font-size: 15px;
            font-family: inherit;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #2f6b45;
            box-shadow: 0 0 0 3px rgba(47, 107, 69, 0.15);
        }
        .btn-submit {
            width: 100%;
            padding: 12px 16px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #2f6b45, #5da96a);
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            transition: transform 0.1s ease;
        }
        .btn-submit:hover { transform: translateY(-1px); }
        .alert {
            padding: 10px 12px;
            border-radius: 10px;
            background: #fde0e0;
            color: #b3261e;
            margin-bottom: 16px;
            font-size: 14px;
        }
        .hint {
            margin-top: 18px;
            text-align: center;
            color: #6b7b6d;
            font-size: 12px;
        }
        .hint code {
            background: #eef8ed;
            padding: 2px 6px;
            border-radius: 4px;
            color: #2f6b45;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand">
            <div class="leaf">L</div>
            <h1>LeafyNest Admin</h1>
            <p>Sign in to manage plants, orders and inventory.</p>
        </div>

        @if($errors->any())
            <div class="alert">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="admin" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-submit">Log In</button>
        </form>

        <p class="hint">Use username <code>admin</code> and password <code>admin123</code>.</p>
    </div>
</body>
</html>