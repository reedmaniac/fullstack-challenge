<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Full Stack Challenge API') }}</title>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-50 dark:bg-gray-900 sm:items-center sm:pt-0">
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
                    <div class="ml-4 text-lg text-gray-800 uppercase tracking-wider">
                        Welcome to the API.
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
