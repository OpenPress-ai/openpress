<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Welcome to OpenPress</title>
        <style>
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
                background-color: #e6f3ff;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .welcome-message {
                font-size: 2.5rem;
                color: #333;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="welcome-message">
            Welcome to OpenPress
        </div>
    </body>
</html>