<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Welcome to OpenPress</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-blue-100 flex items-center justify-center min-h-screen">
        <div class="text-4xl text-gray-800 text-center">
            Welcome to OpenPress
        </div>
    </body>
</html>