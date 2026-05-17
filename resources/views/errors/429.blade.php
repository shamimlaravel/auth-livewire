<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>429 - Too Many Requests</title>
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-screen items-center justify-center bg-gray-50 px-4">
    <div class="w-full max-w-md text-center">
        <h1 class="text-7xl font-bold text-indigo-600">429</h1>
        <h2 class="mt-4 text-2xl font-semibold text-gray-900">{{ __('Too Many Requests') }}</h2>
        <p class="mt-2 text-gray-600">{{ __('You have made too many requests. Please wait before trying again.') }}</p>
        <a href="{{ url('/') }}" class="mt-6 inline-block rounded-lg bg-indigo-600 px-6 py-3 text-sm font-medium text-white hover:bg-indigo-700">
            {{ __('Go Home') }}
        </a>
    </div>
</body>
</html>
