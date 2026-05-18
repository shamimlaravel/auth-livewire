<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>503 - Maintenance</title>
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-screen items-center justify-center bg-surface-50 px-4">
    <div class="w-full max-w-md text-center">
        <h1 class="text-7xl font-bold text-brand-500">503</h1>
        <h2 class="mt-4 text-2xl font-semibold text-surface-900">{{ __('Under Maintenance') }}</h2>
        <p class="mt-2 text-surface-500">{{ __('We are currently performing maintenance. Please check back soon.') }}</p>
    </div>
</body>
</html>
