<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Foodlink</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #FDE9B4; 
            display: flex;
            justify-content: center; 
            align-items: center;     
            min-height: 100vh;
            margin: 0;
        }

        .login-box {
            width: 100%;
            max-width: 420px; 
            text-align: center;
            padding: 20px;
        }

        h2 {
            color: #4B3621; 
            font-size: 56px;
            margin-bottom: 40px;
            font-weight: 800;
        }

        .input-group {
            position: relative;
            margin-bottom: 18px;
        }

        .input-group i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #A0A0A0;
            font-size: 18px;
        }

        input {
            width: 100%;
            padding: 18px 20px 18px 55px;
            border: none;
            border-radius: 18px;
            background-color: #FFFFFF;
            font-size: 16px;
            outline: none;
            color: #333;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }

        input::placeholder {
            color: #C0C0C0;
        }

        button {
            width: 100%;
            padding: 20px;
            background-color: #4B3621;
            color: #FDE9B4;
            border: none;
            border-radius: 18px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 30px;
            transition: opacity 0.2s ease;
        }

        button:hover {
            opacity: 0.9;
        }

        .register {
            margin-top: 35px;
            font-size: 15px;
            color: #4B3621;
        }

        .register a {
            color: #4B3621;
            text-decoration: underline;
            font-weight: 800;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <h2>Sign Up</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="input-group">
                <i class="far fa-user"></i>
                <input type="text" name="name" placeholder="Enter your name" required>
            </div>

            <div class="input-group">
                <i class="far fa-envelope"></i>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password_confirmation" placeholder="Confirm your password" required>
            </div>

            <button type="submit">Create</button>
        </form>

        <div class="register">
            Already have an account? <a href="{{ route('login') }}">Log in</a>
        </div>
    </div>

</body>
</html>