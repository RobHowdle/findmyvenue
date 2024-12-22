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

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
  <div id="preloader" class="animation">
    <div class="decor">
      <div class="bar"></div>
    </div>
    <p>Loading...</p>
  </div>

  <div class="pre-overlay o-1"></div>
  <div class="pre-overlay o-2"></div>

  <div class="flex h-screen w-full flex-col items-center justify-center gap-24 px-2 backdrop-brightness-50">
    <div class="logo--503 justify-self-center">
      <img src="{{ asset('images/yns_logo.png') }}" class="mb-4">
      <a href="https://countdown.yournextshow.co.uk" class="text-white underline">Return To
        Countdown</a>
    </div>

    <div class="text text-center text-white">
      <p class="mb-4 text-4xl">Nice Try!</p>
      <p class="mb-4 text-xl">We're still working on some stuff so things aren't quite ready for you to see yet but...
      </p>
      <p class="text-lg">since you made it all this way here is something fun for you to watch - <a
          href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" target="_blank"
          class="underline hover:font-bold">ENJOY!</a></p>
    </div>
  </div>

  <script>
    jQuery(document).ready(function() {
      var startTime = performance.now(); // Record the start time when the document is ready

      // Function to hide the loader and overlay
      function hideLoader() {
        jQuery("#preloader").delay(100).removeClass("animation").addClass("over");
        jQuery(".pre-overlay").css({
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
      jQuery(window).on('load', function() {
        checkLoadingTime();
      });
    });
  </script>
</body>

</html>
