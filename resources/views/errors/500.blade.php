<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Server Error</title>
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-screen items-center justify-center bg-gray-50 px-4">
    <div class="w-full max-w-md text-center">
        <h1 class="text-7xl font-bold text-indigo-600">500</h1>
        <h2 class="mt-4 text-2xl font-semibold text-gray-900">{{ __('Server Error') }}</h2>
        <p class="mt-2 text-gray-600">{{ __('Something went wrong on our end. Please try again later.') }}</p>
        <a href="{{ url('/') }}" class="mt-6 inline-block rounded-lg bg-indigo-600 px-6 py-3 text-sm font-medium text-white hover:bg-indigo-700">
            {{ __('Go Home') }}
        </a>
    </div>
</body>
</html>
