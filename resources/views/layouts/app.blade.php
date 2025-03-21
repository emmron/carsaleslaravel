<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CarSales') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white shadow mt-auto py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ config('app.name', 'CarSales') }}</h3>
                        <p class="text-gray-600 mt-2">Your trusted platform for buying and selling cars.</p>
                    </div>
                    <div>
                        <h4 class="text-md font-semibold text-gray-800">Quick Links</h4>
                        <ul class="mt-2">
                            <li><a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">Home</a></li>
                            <li><a href="{{ route('listings.index') }}" class="text-blue-600 hover:text-blue-800">Browse Cars</a></li>
                            @auth
                                <li><a href="{{ route('listings.create') }}" class="text-blue-600 hover:text-blue-800">Sell Your Car</a></li>
                                <li><a href="{{ route('listings.my') }}" class="text-blue-600 hover:text-blue-800">My Listings</a></li>
                            @else
                                <li><a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Login</a></li>
                                <li><a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800">Register</a></li>
                            @endauth
                        </ul>
                    </div>
                </div>
                <div class="mt-6 border-t border-gray-200 pt-4">
                    <p class="text-gray-600 text-sm text-center">&copy; {{ date('Y') }} {{ config('app.name', 'CarSales') }}. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
