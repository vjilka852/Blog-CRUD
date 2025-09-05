<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="height:100vh;">
    <div class="card shadow-lg p-4" style="width:400px;">
        <h3 class="text-center mb-4">Register Your Account</h3>

        {{-- Validation error message --}}
        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Register Form --}}
        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div class="mb-3">
                <label>Name</label>
                <input type="text" 
                       name="name" 
                       class="form-control" 
                       value="{{ old('name') }}" 
                       required 
                       autofocus>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" 
                       name="email" 
                       class="form-control" 
                       value="{{ old('email') }}" 
                       required>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" 
                       name="password" 
                       class="form-control" 
                       required>
            </div>

            <div class="mb-3">
                <label>Confirm Password</label>
                <input type="password" 
                       name="password_confirmation" 
                       class="form-control" 
                       required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>

        <div class="text-center mt-3">
            <small>Already have an account? 
                <a href="{{ route('login') }}">Login</a>
            </small>
        </div>
    </div>
</div>

</body>
</html>
