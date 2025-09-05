<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog App</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        {{-- Content from child views --}}
        @yield('content')
    </div>
</body>
</html>
