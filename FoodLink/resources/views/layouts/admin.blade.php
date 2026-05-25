<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Foodlink Admin')</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Plus Jakarta Sans', sans-serif; }

        body {
            display:flex;
            background:#fdfdfd;
            min-height:100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width:280px;
            background:#F8E7C1;
            display:flex;
            flex-direction:column;
            border-right:1px solid #e0e0e0;
            padding:20px 0;
        }

        .brand {
            padding:0 25px;
            font-size:22px;
            font-weight:700;
            color:#6B4F2A;
            margin-bottom:20px;
        }

        .nav {
            display:flex;
            flex-direction:column;
            gap:8px;
            padding:0 15px;
        }

        .nav a {
            text-decoration:none;
            padding:12px 15px;
            border-radius:10px;
            color:#4A4A4A;
            font-size:14px;
            display:flex;
            align-items:center;
            gap:10px;
            transition:.2s;
        }

        .nav a i {
            color:#6B4F2A;
            width:18px;
        }

        .nav a:hover {
            background:rgba(107,79,42,0.1);
        }

        .nav a.active {
            background:#6B4F2A;
            color:white;
        }

        .nav a.active i {
            color:white;
        }

        .logout {
            margin-top:auto;
            padding:15px;
        }

        .logout button {
            width:100%;
            padding:12px;
            border:none;
            background:none;
            color:#d9534f;
            text-align:left;
            cursor:pointer;
            border-radius:10px;
        }

        .logout button:hover {
            background:rgba(217,83,79,0.1);
        }

        /* MAIN */
        .main {
            flex:1;
            padding:25px;
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<!-- <div class="sidebar">

    <div class="brand">Foodlink</div>

    <div class="nav">

        {{-- DASHBOARD --}}
        <a href="{{ route('admin.dashboard') }}"
           class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa fa-home"></i> Dashboard
        </a>

        {{-- VALIDASI --}}
        <a href="{{ route('admin.validasi.index') ?? '#' }}"
           class="{{ request()->routeIs('admin.validasi.*') ? 'active' : '' }}">
            <i class="fa fa-check"></i> Validasi Donasi
        </a>

        {{-- RETUR --}}
        <a href="{{ route('admin.retur.index') ?? '#' }}"
           class="{{ request()->routeIs('admin.retur.*') ? 'active' : '' }}">
            <i class="fa fa-rotate-left"></i> Retur Donasi
        </a>

        <a href="{{ route('chat.user') }}"
   class="{{ request()->routeIs('chat.user') ? 'active' : '' }}">
    <i class="fa fa-comments"></i> Chat
</a>

        {{-- REPORT --}}
        <a href="{{ route('admin.report.index') ?? '#' }}"
           class="{{ request()->routeIs('admin.report.*') ? 'active' : '' }}">
            <i class="fa fa-chart-pie"></i> Report
        </a>

    </div>

    <div class="logout">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">
                <i class="fa fa-sign-out"></i> Logout
            </button>
        </form>
    </div>

</div>

<!-- MAIN CONTENT -->
<!-- <div class="main">
    @yield('content')
</div>

</body>
</html> --> --> -->