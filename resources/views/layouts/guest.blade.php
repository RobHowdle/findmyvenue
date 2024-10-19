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

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/dd6bff54df.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="relative font-sans antialiased">
  <div class="absolute inset-0 bg-cover bg-fixed bg-center bg-no-repeat"
    style="background-image: url('{{ asset('storage/images/system/hero-bg.jpg') }}'); z-index: -1;"></div>
  <div id="preloader" class="animation">
    <div class="decor">
      <div class="bar"></div>
    </div>
    <p>Loading...</p>
  </div>

  <div class="pre-overlay o-1"></div>
  <div class="pre-overlay o-2"></div>
  @if (Route::has('login'))
    <nav class="fixed z-10 w-full bg-yns_dark_blue">
      <div class="mx-auto flex max-w-screen-2xl flex-wrap items-center justify-between px-2 py-4 md:px-4 md:py-8">
        <a href="{{ url('/') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
          <img src="{{ asset('images/system/yns_logo.png') }}" class="h-16"
            alt="{{ config('app.name', 'Laravel') }} Logo" />
          <span
            class="self-center whitespace-nowrap text-2xl font-semibold dark:text-white">{{ config('app.name') }}</span>
        </a>
        <button data-collapse-toggle="navbar-default" type="button"
          class="inline-flex h-10 w-10 items-center justify-center rounded-lg p-2 text-sm text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600 md:hidden"
          aria-controls="navbar-default" aria-expanded="false">
          <span class="sr-only">Open main menu</span>
          <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M1 1h15M1 7h15M1 13h15" />
          </svg>
        </button>
        <div class="hidden w-full md:block md:w-auto" id="navbar-default">
          <ul
            class="mt-4 flex flex-col rounded-lg border border-gray-100 bg-gray-50 p-4 font-medium rtl:space-x-reverse dark:border-gray-700 dark:bg-gray-800 md:mt-0 md:flex-row md:space-x-8 md:border-0 md:bg-white md:p-0 md:dark:bg-gray-900">
            <li>
              <a href="{{ url('/venues') }}"
                class="{{ request()->is('venues*') ? 'dark:text-yns_yellow' : '' }} font-heading text-2xl font-semibold text-white hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-white dark:hover:text-yns_yellow">Venues</a>
            </li>
            <li>
              <a href="{{ url('/promoters') }}"
                class="{{ request()->is('promoters*') ? 'dark:text-yns_yellow' : '' }} font-heading text-2xl font-semibold text-white hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-white dark:hover:text-yns_yellow">Promoters</a>
            </li>
            <li>
              <a href="{{ url('/other') }}"
                class="{{ request()->is('other*') ? 'dark:text-yns_yellow' : '' }} font-heading text-2xl font-semibold text-white hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-white dark:hover:text-yns_yellow">Other</a>
            </li>
            @auth
              <li>
                <a href="{{ url('/dashboard') }}"
                  class="font-heading text-2xl font-semibold text-white hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-white dark:hover:text-yns_yellow">Dashboard</a>
              </li>
            @else
              <li>
                <a href="{{ url('/login') }}"
                  class="font-heading text-2xl font-semibold text-white hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-white dark:hover:text-yns_yellow">Login</a>
              </li>
            @endauth
          </ul>
        </div>
      </div>
    </nav>
  @endif
  <div class="flex min-h-screen flex-col">
    <div class="flex-grow backdrop-brightness-50">
      {{ $slot }}
    </div>

    <footer class="w-full text-white transition duration-150 ease-in-out hover:text-yns_yellow">
      <div class="w-full bg-yns_dark_blue px-2 py-4">
        <div class="mx-auto flex max-w-screen-2xl flex-wrap items-center justify-between">
          <a href="{{ url('/') }}"
            class="flex w-60 items-center space-x-3 transition duration-150 ease-in-out hover:text-yns_yellow rtl:space-x-reverse">
            <img src="{{ asset('images/system/yns_logo.png') }}" class="h-16"
              alt="{{ config('app.name', 'Laravel') }} Logo" />
            <span
              class="self-center whitespace-nowrap text-2xl font-semibold dark:text-white">{{ config('app.name') }}</span>
          </a>
          <ul class="flex w-60 flex-row gap-8 font-heading">
            <li>
              <a href="#"
                class="text-white transition duration-150 ease-in-out hover:text-yns_yellow hover:underline">About</a>
            </li>
            <li>
              <a href="#"
                class="text-white transition duration-150 ease-in-out hover:text-yns_yellow hover:underline">Credits</a>
            </li>

            <li>
              <a href="#"
                class="text-white transition duration-150 ease-in-out hover:text-yns_yellow hover:underline">Contact</a>
            </li>
          </ul>
          <ul class="flex w-60 flex-col gap-2 text-right font-heading">
            <li>
              <a href="https://www.youtube.com/watch?v=Rs2z7OA3XKI" target="_blank"
                class="text-white transition duration-150 ease-in-out hover:text-yns_yellow">We didn't know
                what to put
                here so here is a funny video</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="w-full bg-black px-2 py-2">
        <div class="mx-auto flex max-w-screen-2xl flex-wrap items-center justify-between">
          <a href="#"
            class="text-white transition duration-150 ease-in-out hover:text-yns_yellow hover:underline">Privacy
            Policy</a>
          <p class="text-yns_med_gray">&copy; {{ env('APP_NAME') }} All Rights Reserved {{ date('Y') }}</p>
          <a href="#"
            class="text-white transition duration-150 ease-in-out hover:text-yns_yellow hover:underline">Terms &
            Conditions</a>
        </div>
      </div>
    </footer>
  </div>
  @stack('scripts')
  <script>
    $(document).ready(function() {
      var startTime = performance.now(); // Record the start time when the document is ready

      // Function to hide the loader and overlay
      function hideLoader() {
        $("#preloader").delay(100).removeClass("animation").addClass("over");
        $(".pre-overlay").css({
          "height": "0%"
        });
      }

      // Function to calculate loading time and decide whether to show the loader
      function checkLoadingTime() {
        var endTime = performance.now(); // Record the end time after the document is fully loaded
        var loadingTime = endTime - startTime; // Calculate the loading time in milliseconds
        // Check if the loading time exceeds a threshold (e.g., 1000 milliseconds)
        if (loadingTime > 1000) {
          // Show the loader if loading time exceeds the threshold
          setTimeout(hideLoader, 4000);
        } else {
          // Hide the loader if loading time is fast
          hideLoader();
        }
      }

      // Call the function to check loading time when the document is fully loaded
      $(window).on('load', function() {
        checkLoadingTime();
      });
    });
  </script>
</body>

</html>
