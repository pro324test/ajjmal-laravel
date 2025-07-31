<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Multivendor E-commerce') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800">
                        Multivendor Store
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-800">Products</a>
                    <a href="{{ route('cart') }}" class="text-gray-600 hover:text-gray-800">Cart</a>
                    <a href="{{ route('vendor.dashboard') }}" class="text-gray-600 hover:text-gray-800">Vendor</a>
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-800">Admin</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-8">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>