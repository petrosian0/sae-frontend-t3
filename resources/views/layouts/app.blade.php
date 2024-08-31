<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Add your CSS here -->
</head>
<body>
    @include('layouts.navigation') <!-- Include the navigation bar -->
    
    <div class="content">
        @yield('content')
    </div>
</body>
</html>
