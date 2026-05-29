<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .left {
            width: 50%;
            background: url('/img/Indian Food.jpeg') no-repeat center center/cover;
        }

        .right {
            width: 50%;
            background-color: #EAD3A7;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 90px;
        }

        .login-box {
            width: 360px;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 8px;
            text-align: center;
        }

        p {
            font-size: 14px; 
            color: #666;
            margin-bottom: 25px;
            text-align: center;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box; /* Tambahan agar padding tidak merusak lebar */
        }

        .remember {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            margin-bottom: 18px;
        }

        .remember label {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .remember input[type="checkbox"] {
            width: auto;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #6B4F2A;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
        }

        button:hover {
            background-color: #5a3f22;
        }

        .register {
            text-align: center;
            font-size: 13px;
            margin-top: 15px;
        }

        a {
            color: blue;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left"></div>

        <div class="right">
            <div class="login-box">
                <h2>Welcome back</h2>
                <p>Take a moment. Let's continue.</p>

                {{-- Notifikasi Error Jika Login Gagal --}}
                @if($errors->any())
                    <p style="color: red; font-size: 12px; text-align: center;">{{ $errors->first() }}</p>
                @endif

                <form method="POST" action="/login">
                    @csrf
                    <input type="email" name="email" placeholder="Enter your email" required>
                    <input type="password" name="password" placeholder="Enter your password" required>

                    <div class="remember">
                        <label>
                            <input type="checkbox" name="remember"> Remember me
                        </label>
                        {{-- Rute yang diperbarui ke password.request --}}
                        <a href="{{ route('password.request') }}">Forgot Password</a>
                    </div>

                    <button type="submit">Login</button>
                </form>

                <div class="register">
                    New here? <a href="{{ route('register') }}">Create an account</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>