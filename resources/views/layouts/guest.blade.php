<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
  <div class="flex min-h-screen flex-col items-center bg-hero-bg pt-6 sm:justify-center sm:pt-0">
    <div>
      @if (Route::has('login'))
        <div class="z-10 flex w-full justify-between p-6 text-right sm:fixed sm:right-0 sm:top-0">
          <a href="{{ url('/') }}"
            class="font-heading text-2xl font-semibold text-white hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-white dark:hover:text-gray-400">Venues</a>
          <a href="{{ url('/venue') }}"
            class="font-heading text-2xl font-semibold text-white hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-white dark:hover:text-gray-400">Locations</a>
          <a href="{{ url('/') }}"
            class="font-heading text-2xl font-semibold text-white hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-white dark:hover:text-gray-400">Promoters</a>
          <a href="{{ url('/') }}"
            class="font-heading text-2xl font-semibold text-white hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-white dark:hover:text-gray-400">Other</a>
          @auth
            <a href="{{ url('/dashboard') }}"
              class="font-heading text-2xl font-semibold text-white hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-white dark:hover:text-gray-400">Dashboard</a>
          @else
            <a href="{{ route('login') }}"
              class="font-heading text-2xl font-semibold text-white hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-white dark:hover:text-gray-400">Log
              In</a>
          @endauth
        </div>
      @endif
    </div>

    <div class="w-full overflow-hidden px-6 py-4">
      {{ $slot }}
    </div>
  </div>
</body>

</html>
