@props(['dashboardType', 'modules'])

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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  <!-- Include Summernote CSS -->
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

  <!-- Include jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

  <!-- Include Summernote JS -->
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
  <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>

  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <!-- Include Flatpickr -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/dd6bff54df.js" crossorigin="anonymous"></script>

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Include Alpine.js -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

  <!-- Google Maps API -->
  <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMjlXwDOk74oMDPgOp4YWdWxPa5xtHGA&libraries=places&callback=initialize"
    async defer></script>

  <!-- Full Calendar -->
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="relative font-sans antialiased">
  <div class="absolute inset-0 bg-cover bg-fixed bg-center bg-no-repeat"
    style="background-image: url('{{ asset('storage/images/system/hero-bg.jpg') }}'); z-index: -1;"></div>
  <div class="min-h-screen text-white">
    @include('layouts.navigation', ['dashboardType' => $dashboardType, 'modules' => $modules])

    <!-- Page Heading -->
    @if (isset($header))
      <header class="bg-white shadow dark:bg-gray-800">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
          {{ $header }}
        </div>
      </header>
    @endif

    <div class="{{ request()->routeIs('profile.*') ? '' : 'px-2' }} flex min-h-screen flex-col">
      <div class="flex-grow backdrop-brightness-50">
        {{ $slot }}
      </div>
      <x-notes></x-notes>
    </div>
  </div>

  @stack('scripts')
</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>

<script>
  // Address Input
  function initialize() {
    // All your Google Maps initialization code
    $("form").on("keyup keypress", function(e) {
      var keyCode = e.keyCode || e.which;
      if (keyCode === 13) {
        e.preventDefault();
        return false;
      }
    });

    const locationInputs = document.getElementsByClassName("map-input");
    const autocompletes = [];
    const geocoder = new google.maps.Geocoder();

    for (let i = 0; i < locationInputs.length; i++) {
      const input = locationInputs[i];
      const fieldKey = input.id.replace("-input", "");
      const isEdit =
        document.getElementById(fieldKey + "-latitude").value != "" &&
        document.getElementById(fieldKey + "-longitude").value != "";

      const latitude =
        parseFloat(document.getElementById(fieldKey + "-latitude").value) ||
        59.339024834494886;
      const longitude =
        parseFloat(
          document.getElementById(fieldKey + "-longitude").value
        ) || 18.06650573462189;

      const map = new google.maps.Map(
        document.getElementById(fieldKey + "-map"), {
          center: {
            lat: latitude,
            lng: longitude
          },
          zoom: 13,
        }
      );

      const marker = new google.maps.Marker({
        map: map,
        position: {
          lat: latitude,
          lng: longitude
        },
      });

      marker.setVisible(isEdit);

      const autocomplete = new google.maps.places.Autocomplete(input);
      autocomplete.key = fieldKey;
      autocompletes.push({
        input: input,
        map: map,
        marker: marker,
        autocomplete: autocomplete,
      });
    }

    // Set up listeners for each autocomplete
    autocompletes.forEach(({
      input,
      autocomplete,
      map,
      marker
    }) => {
      google.maps.event.addListener(
        autocomplete,
        "place_changed",
        function() {
          marker.setVisible(false);
          const place = autocomplete.getPlace();

          let postalTown = "";
          place.address_components.forEach((component) => {
            if (component.types.includes("postal_town")) {
              postalTown = component.long_name;
            }
          });

          const postalTownComponent = place.address_components.find(
            (component) => component.types.includes("postal_town")
          );
          if (postalTownComponent) {
            document.getElementById("postal-town-input").value =
              postalTownComponent.long_name;
          }

          geocoder.geocode({
              placeId: place.place_id
            },
            function(results, status) {
              if (status === google.maps.GeocoderStatus.OK) {
                const lat = results[0].geometry.location.lat();
                const lng = results[0].geometry.location.lng();
                setLocationCoordinates(autocomplete.key, lat, lng);
              }
            }
          );

          if (!place.geometry) {
            window.alert(
              "No details available for input: '" + place.name + "'"
            );
            input.value = "";
            return;
          }

          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
          }
          marker.setPosition(place.geometry.location);
          marker.setVisible(true);
        }
      );
    });
  }

  function setLocationCoordinates(key, lat, lng) {
    const latitudeField = document.getElementById(key + "-latitude");
    const longitudeField = document.getElementById(key + "-longitude");
    latitudeField.value = lat;
    longitudeField.value = lng;
  }
</script>
