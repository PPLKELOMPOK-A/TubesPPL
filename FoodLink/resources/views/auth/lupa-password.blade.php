<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #EAD3A7; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        }
        .box { 
            width: 360px; 
            background: white; 
            padding: 40px; 
            border-radius: 12px; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
        }
        h2 { 
            text-align: center; 
            margin-bottom: 8px; 
            color: #333; 
        }
        p { 
            font-size: 13px; 
            color: #666; 
            text-align: center; 
            margin-bottom: 25px; 
        }
        input { 
            width: 100%; 
            padding: 12px; 
            margin-bottom: 15px; 
            border: 1px solid #ccc; 
            border-radius: 6px; 
            box-sizing: border-box; 
        }
        button { 
            width: 100%; 
            padding: 12px; 
            background-color: #6B4F2A; 
            color: white; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-size: 15px; 
        }
        button:hover { 
            background-color: #5a3f22; 
        }
        .alert { 
            font-size: 12px; 
            margin-bottom: 15px; 
            padding: 10px; 
            border-radius: 4px; 
        }
        .error { 
            color: #721c24; 
            background-color: #f8d7da; 
            border: 1px solid #f5c6cb;
        }
        .back-login { 
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
    <div class="box">
        <h2>Forgot Password</h2>
        <p>Enter your email to reset your password.</p>

        {{-- Menampilkan pesan error jika email tidak ditemukan / sesi reset habis --}}
        @if($errors->any())
            <div class="alert error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.check') }}">
            @csrf
            <input type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required>
            <button type="submit">Continue</button>
        </form>

        <div class="back-login">
            <a href="{{ route('login') }}">Back to Login</a>
        </div>
    </div>
</body>
</html>