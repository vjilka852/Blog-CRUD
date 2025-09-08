<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="height:100vh;">
    <div class="card shadow-lg p-4" style="width:400px;">

        {{-- If user is already logged in --}}
        @auth
            <h3 class="text-center mb-4">You are already logged in</h3>
            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit" class="btn btn-danger w-100">Logout</button>
            </form>
        @endauth

        {{-- If user is NOT logged in --}}
        @guest
            <h3 class="text-center mb-4">Login</h3>

            {{-- General Error Message --}}
          

            <form method="POST" action="{{ route('login.post') }}" novalidate>
                @csrf
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           name="password" 
                           required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            {{-- Register option --}}
            <div class="text-center mt-3">
                <small>Don’t have an account? 
                    <a href="{{ route('register') }}">Register</a>
                </small>
            </div>
        @endguest

    </div>
</div>

</body>
</html>
