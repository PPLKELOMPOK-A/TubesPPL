<body>
    <div class="container">
        <div class="left"></div>
        <div class="right">
            <div class="login-box">
                <h2>Join Foodlink</h2>
                <p>Create your account today.</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <input type="text" name="name" placeholder="Full Name" required>
                    <input type="email" name="email" placeholder="Email Address" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" required>

                    <button type="submit">Register</button>
                </form>

                <div class="register">
                    Already have an account? <a href="{{ route('login') }}">Login here</a>
                </div>
            </div>
        </div>
    </div>
</body>