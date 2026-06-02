<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@auth Change Password @else Reset Password @endauth</title>
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
        .success { 
            color: #155724; 
            background-color: #d4edda; 
            border: 1px solid #c3e6cb;
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
        <h2>@auth Change Password @else Reset Password @endauth</h2>
        <p>Set your new password to secure your account.</p>

        {{-- Menampilkan pesan sukses --}}
        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        {{-- Menampilkan pesan error --}}
        @if($errors->any())
            <div class="alert error">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('profil.update-password') }}">
            @csrf
            
            {{-- Input Email muncul jika belum login (lupa password) --}}
            @guest
                <input type="email" name="email" value="{{ session('reset_email') }}" placeholder="Enter your registered email" required readonly>
            @endguest

            {{-- Input Password Lama muncul jika sudah login (edit profil) --}}
            @auth
                <input type="password" name="current_password" placeholder="Current Password" required>
            @endauth

            <input type="password" name="password" placeholder="New Password" required>
            <input type="password" name="password_confirmation" placeholder="Confirm New Password" required>
            
            <button type="submit">Save Changes</button>
        </form>

        @guest
            <p style="font-size: 11px; margin-top: 10px; color: #888; text-align: center;">
                *Jika Anda lupa password sama sekali, hubungi admin.
            </p>
        @endguest

        <div class="back-login">
            @auth
                <a href="{{ route('profil.index') }}">Back to Profile</a>
            @else
                <a href="{{ route('login') }}">Back to Login</a>
            @endauth
        </div>
    </div>
</body>
</html>