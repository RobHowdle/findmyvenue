<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
      {{ __('Other Services') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
        <div class="count-wrapper p-6 text-gray-900 dark:text-gray-100">
          <p class="mt-4 text-xl">Create Other Service</p>
          <span class="text-sm">Create a photographer, videographer, designer etc</span>
          @if ($errors->any())
            <div class="alert-danger alert">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <form class="mt-2" action="{{ route('admin.save-other') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="group relative z-0 mb-5 w-full">
              <label class="text-sm font-medium text-gray-900 dark:text-gray-300">Service<span
                  class="required">*</span></label>
              <select id="service" name="service"
                class="form-select mt-1 block rounded-lg border-gray-300 bg-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                <option value="">Please Select</option>
                @foreach ($serviceList as $service)
                  <option value="{{ $service->id }}">{{ $service->service_name }}</option>
                @endforeach
              </select>
            </div>

            <div id="photographer-form" style="display: none">
              @include('admin.forms.new-photographer-form')
            </div>

            <div class="group relative z-0 mb-5 w-full">
              <button type="submit"
                class="w-full rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 sm:w-auto">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
<script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMjlXwDOk74oMDPgOp4YWdWxPa5xtHGA&libraries=places&callback=initialize"
  async defer></script>
<script>
  function initialize() {

    $('form').on('keyup keypress', function(e) {
      var keyCode = e.keyCode || e.which;
      if (keyCode === 13) {
        e.preventDefault();
        return false;
      }
    });
    const locationInputs = document.getElementsByClassName("map-input");

    const autocompletes = [];
    const geocoder = new google.maps.Geocoder;
    for (let i = 0; i < locationInputs.length; i++) {

      const input = locationInputs[i];
      const fieldKey = input.id.replace("-input", "");
      const isEdit = document.getElementById(fieldKey + "-latitude").value != '' && document.getElementById(
        fieldKey +
        "-longitude").value != '';

      const latitude = parseFloat(document.getElementById(fieldKey + "-latitude").value) || 59.339024834494886;
      const longitude = parseFloat(document.getElementById(fieldKey + "-longitude").value) || 18.06650573462189;

      const map = new google.maps.Map(document.getElementById(fieldKey + '-map'), {
        center: {
          lat: latitude,
          lng: longitude
        },
        zoom: 13
      });
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
        autocomplete: autocomplete
      });
    }

    for (let i = 0; i < autocompletes.length; i++) {
      const input = autocompletes[i].input;
      const autocomplete = autocompletes[i].autocomplete;
      const map = autocompletes[i].map;
      const marker = autocompletes[i].marker;

      google.maps.event.addListener(autocomplete, 'place_changed', function() {
        marker.setVisible(false);
        const place = autocomplete.getPlace();

        let postalTown = '';
        place.address_components.forEach(component => {
          if (component.types.includes('postal_town')) {
            postalTown = component.long_name;
          }
        });

        const postalTownComponent = place.address_components.find(component => component.types.includes(
          'postal_town'));
        if (postalTownComponent) {
          document.getElementById('postal-town-input').value = postalTownComponent.long_name;
        }

        geocoder.geocode({
          'placeId': place.place_id
        }, function(results, status) {
          if (status === google.maps.GeocoderStatus.OK) {
            const lat = results[0].geometry.location.lat();
            const lng = results[0].geometry.location.lng();
            setLocationCoordinates(autocomplete.key, lat, lng);
          }
        });

        if (!place.geometry) {
          window.alert("No details available for input: '" + place.name + "'");
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

      });
    }

    function setLocationCoordinates(key, lat, lng) {
      const latitudeField = document.getElementById(key + "-" + "latitude");
      const longitudeField = document.getElementById(key + "-" + "longitude");
      latitudeField.value = lat;
      longitudeField.value = lng;
    }
  }
</script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    var serviceSelect = document.getElementById("service");
    var photographerInclude = document.getElementById("photographer-form");

    function togglePhotographerInclude() {
      if (serviceSelect.value === "1") {
        photographerInclude.style.display = "block";
      } else {
        photographerInclude.style.display = "none";
      }
    }

    // Initial call to togglePhotographerInclude
    togglePhotographerInclude();

    // Add event listener for change event on serviceSelect
    serviceSelect.addEventListener("change", function() {
      togglePhotographerInclude();
    });
  });
</script>
