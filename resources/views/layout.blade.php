<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel User CRUD</title>

    {{-- ✅ Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }
        .card {
            border-radius: 15px;
        }
        .card-header {
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .btn {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    {{-- ✅ Navbar (optional) --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            {{-- BLOG brand link always goes to login page --}}
            <a class="navbar-brand fw-bold text-white" href="{{ route('login') }}">BLOG</a>
    
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
    
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item mx-2">
                        <a class="nav-link text-white" href="{{ route('users.index') }}">Users</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link text-white" href="{{ route('blogs.index') }}">Blogs</a>
                    </li>
                </ul>
            </div>
    
            <ul class="navbar-nav">
                @auth
                    <li class="nav-item mx-2">
                        <span class="nav-link text-white">Hello, {{ Auth::user()->name }}</span>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light">Logout</button>
                        </form>
                    </li>
                @endauth
                @guest
                    <li class="nav-item mx-2">
                        <a href="{{ route('login') }}" class="nav-link text-white">Login</a>
                    </li>
                @endguest
            </ul>
        </div>
    </nav>
    
    

    <div class="container py-4">
        @yield('content')
    </div>

    {{-- ✅ Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
