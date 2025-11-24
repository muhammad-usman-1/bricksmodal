<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <link rel="stylesheet" href= {{ asset('css/output.css') }}>
        @yield('styles')
    </head>

    <body>
        <div class="bg-white">
            <!-- Responsive Main Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 mt-5 lg:gap-0 w-full">
                @include('partials.admin-menu')
                @yield('content')
            </div>

            
        </div>
    </body>

    </html>