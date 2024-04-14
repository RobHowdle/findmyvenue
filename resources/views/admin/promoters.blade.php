<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
      {{ __('Promoters') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
        <div class="count-wrapper p-6 text-gray-900 dark:text-gray-100">
          <p class="text-2xl">Active Promoters: <span>{{ $promoterCount }}</span></p>
          <p class="mt-4 text-xl">Create New Promoter</p>
          <span class="text-sm">Due to the large amounts of information, we suggest typing your information in a notes
            app and pasting
            them in. Please ensure there are no weird characters before pasting!</span>
          @if ($errors->any())
            <div class="alert-danger alert">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <form class="mt-2" action="{{ route('admin.new-promoter') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="group relative z-0 mb-5 w-full">
              <input type="search" name="address-input" id="address-input" value="{{ old('address-input') }}"
                class="map-input peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
                placeholder=" " required />
              <label for="address-input"
                class="absolute top-3 -z-10 origin-[0] -translate-y-6 scale-75 transform text-sm text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:start-0 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:font-medium peer-focus:text-blue-600 rtl:peer-focus:translate-x-1/4 dark:text-gray-400 peer-focus:dark:text-blue-500">Location
                - Where are you based?<span class="required">*</span></label>
              @error('address-input')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="group relative z-0 mb-5 w-full">
              <input type="text" name="promoter_name" id="promoter_name" value="{{ old('promoter_name') }}"
                class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
                placeholder=" " required />
              <label for="promoter_name"
                class="absolute top-3 -z-10 origin-[0] -translate-y-6 scale-75 transform text-sm text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:start-0 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:font-medium peer-focus:text-blue-600 rtl:peer-focus:left-auto rtl:peer-focus:translate-x-1/4 dark:text-gray-400 peer-focus:dark:text-blue-500">
                Name<span class="required">*</span>
              </label>
              @error('promoter_name')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>


            <div id="address-map-container" style="width: 100%; height: 400px; display: none;">
              <div style="width: 100%; height: 100%;" id="address-map"></div>
            </div>

            <div class="group relative z-0 mb-5 hidden w-full">
              <input
                class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
                type="text" id="postal-town-input" name="postal-town-input" placeholder="Postal Town Input"
                value="{{ old('postal-town-input') }}">
              <input
                class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
                type="text" id="address-latitude" name="latitude" placeholder="Latitude"
                value="{{ old('latitude') }}">
              <input
                class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
                type="text" id="address-longitude" name="longitude" placeholder="Longitude"
                value="{{ old('longitude') }}">
            </div>

            <div class="group relative z-0 mb-5 w-full">
              <input
                class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
                aria-describedby="promoter_logo" name="promoter_logo" id="promoter_logo" type="file">
              <label
                class="absolute top-3 -z-10 origin-[0] -translate-y-6 scale-75 transform text-sm text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:start-0 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:font-medium peer-focus:text-blue-600 rtl:peer-focus:translate-x-1/4 dark:text-gray-400 peer-focus:dark:text-blue-500"
                for="promoter_logo">Upload Logo<span class="required">*</span></label>
              @error('promoter_logo')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="group relative z-0 mb-5 w-full">
              <textarea name="promoter_about_me" id="promoter_about_me" value="{{ old('promoter_about_me') }}"
                class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
                placeholder=" " required></textarea>
              <label for="promoter_about_me"
                class="absolute top-3 -z-10 origin-[0] -translate-y-6 scale-75 transform text-sm text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:start-0 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:font-medium peer-focus:text-blue-600 rtl:peer-focus:left-auto rtl:peer-focus:translate-x-1/4 dark:text-gray-400 peer-focus:dark:text-blue-500">
                About Me<span class="required">*</span>
              </label>
              @error('promoter_about_me')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="group relative z-0 mb-5 w-full">
              <textarea name="promoter_my_venues" id="promoter_my_venues" value="{{ old('promoter_my_venues') }}"
                class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
                placeholder=" " required></textarea>
              <label for="promoter_my_venues"
                class="absolute top-3 -z-10 origin-[0] -translate-y-6 scale-75 transform text-sm text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:start-0 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:font-medium peer-focus:text-blue-600 rtl:peer-focus:left-auto rtl:peer-focus:translate-x-1/4 dark:text-gray-400 peer-focus:dark:text-blue-500">
                My Venues<span class="required">*</span>
              </label>
              @error('promoter_my_venues')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="group relative z-0 mb-5 w-full">
              <label class="text-sm font-medium text-gray-900 dark:text-gray-300">My Venues<span
                  class="required">*</span></label>
              <div class="mt-4 grid grid-cols-3 gap-4">
                <!-- Genres -->
                @foreach ($venuesByTown as $townVenue)
                  <div>
                    <div class="accordion" id="accordion-container">
                      <div class="accordion-item">
                        <input type="checkbox"
                          class="venues-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
                          id="all-venues-{{ $townVenue->postal_town }}" name="venues[]"
                          value="All {{ $townVenue->postal_town }}"
                          {{ in_array('All ' . $townVenue->postal_town, old('venues', [])) ? 'checked' : '' }}>
                        <label for="all-venues-{{ $townVenue->postal_town }}"
                          class="accordion-title ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">All
                          {{ $townVenue->postal_town }}</label>
                        @error('venues[]')
                          <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="accordion-content">
                          @php
                            $venueNames = explode(',', $townVenue->venue_names);
                          @endphp
                          @foreach ($venueNames as $venue)
                            <div class="checkbox-wrapper">
                              <input type="checkbox"
                                class="venue-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
                                id="venue-{{ $townVenue->id }}" name="venues[]" value="{{ $venue }}"
                                {{ in_array($venue, old('venues', [])) ? 'checked' : '' }}>
                              <label class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                                for="venue-{{ $townVenue->id }}">{{ $venue }}</label>
                            </div>
                          @endforeach
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

            <div class="group relative z-0 mb-5 w-full">
              <label class="text-sm font-medium text-gray-900 dark:text-gray-300">Preferred Band Types</label>
              <div class="mt-4 grid grid-cols-3 gap-4">
                <div class="flex items-center">
                  <input id="all-types" name="band_type[]" type="checkbox" value="all"
                    class="band-type-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
                    {{ in_array('all', old('band_type', [])) ? 'checked' : '' }} />
                  <label for="all-types" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">All
                    Types</label>
                </div>
                <div class="flex items-center">
                  <input id="original-bands" name="band_type[]" type="checkbox" value="original-bands"
                    class="band-type-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
                    {{ in_array('original-bands', old('band_type', [])) ? 'checked' : '' }} />
                  <label for="original-bands"
                    class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Original</label>
                </div>
                <div class="flex items-center">
                  <input id="cover-bands" name="band_type[]" type="checkbox" value="cover-bands"
                    class="band-type-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
                    {{ in_array('cover-bands', old('band_type', [])) ? 'checked' : '' }} />
                  <label for="cover-bands"
                    class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Covers</label>
                </div>
                <div class="flex items-center">
                  <input id="tribute-bands" name="band_type[]" type="checkbox" value="tribute-bands"
                    class="band-type-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
                    {{ in_array('tribute-bands', old('band_type', [])) ? 'checked' : '' }} />
                  <label for="tribute-bands"
                    class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tributes</label>
                </div>
              </div>
            </div>


            <div class="group relative z-0 mb-5 w-full">
              <label class="text-sm font-medium text-gray-900 dark:text-gray-300">Preferred Genre(s) - <span>Yes,
                  there
                  is a lot</span></label>
              <div class="mt-4 grid grid-cols-3 gap-4">
                <!-- "All Genres" checkbox -->
                <div>
                  <div class="flex items-center">
                    <input id="all-genres" name="all-genres" type="checkbox" value=""
                      class="focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800" />
                    <label for="all-genres" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">All
                      Genres</label>
                  </div>
                </div>
                <!-- Genres -->
                @foreach ($genres as $index => $genre)
                  <div>
                    <div class="accordion" id="accordion-container">
                      <div class="accordion-item">
                        <input type="checkbox"
                          class="genre-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
                          id="all-genre-{{ $index }}" name="genres[]" value="All {{ $genre['name'] }}"
                          {{ in_array('All ' . $genre['name'], old('genres', [])) ? 'checked' : '' }}>
                        <label for="all-genre-{{ $index }}"
                          class="accordion-title ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">All
                          {{ $genre['name'] }}</label>
                        @error('genres[]')
                          <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <div class="accordion-content">
                          @foreach ($genre['subgenres'] as $subIndex => $subgenre)
                            <div class="checkbox-wrapper">
                              <input type="checkbox"≈
                                class="subgenre-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
                                id="subgenre-{{ $index }}-{{ $subIndex }}" name="genres[]"
                                value="{{ $subgenre }}"
                                {{ in_array($subgenre, old('genres', [])) ? 'checked' : '' }}>
                              <label class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                                for="subgenre-{{ $index }}-{{ $subIndex }}">{{ $subgenre }}</label>
                            </div>
                          @endforeach
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>


            <div class="group relative z-0 mb-5 w-full">
              <input type="text" name="promoter_contact_number" id="promoter_contact_number"
                value="{{ old('promoter_contact_number') }}"
                class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
                placeholder=" " />
              <label for="promoter_contact_number"
                class="absolute top-3 -z-10 origin-[0] -translate-y-6 scale-75 transform text-sm text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:start-0 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:font-medium peer-focus:text-blue-600 rtl:peer-focus:left-auto rtl:peer-focus:translate-x-1/4 dark:text-gray-400 peer-focus:dark:text-blue-500">
                Contact Number
              </label>
              @error('promoter_contact_number')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="group relative z-0 mb-5 w-full">
              <input type="text" name="promoter_contact_email" id="promoter_contact_email"
                value="{{ old('promoter_contact_email') }}"
                class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
                placeholder=" " />
              <label for="promoter_contact_email"
                class="absolute top-3 -z-10 origin-[0] -translate-y-6 scale-75 transform text-sm text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:start-0 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:font-medium peer-focus:text-blue-600 rtl:peer-focus:left-auto rtl:peer-focus:translate-x-1/4 dark:text-gray-400 peer-focus:dark:text-blue-500">
                Contact Email
              </label>
              @error('promoter_contact_email')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="group relative z-0 mb-5 w-full">
              <input type="text" name="promoter_contact_links" id="promoter_contact_links"
                value="{{ old('promoter_contact_links') }}"
                class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
                placeholder=" " />
              <label for="promoter_contact_links"
                class="absolute top-3 -z-10 origin-[0] -translate-y-6 scale-75 transform text-sm text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:start-0 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:font-medium peer-focus:text-blue-600 rtl:peer-focus:left-auto rtl:peer-focus:translate-x-1/4 dark:text-gray-400 peer-focus:dark:text-blue-500">
                Contact Links (Separate by comma)
              </label>
              @error('promoter_contact_links')
                <span class="text-danger">{{ $message }}</span>
              @enderror
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
  }

  function setLocationCoordinates(key, lat, lng) {
    const latitudeField = document.getElementById(key + "-" + "latitude");
    const longitudeField = document.getElementById(key + "-" + "longitude");
    latitudeField.value = lat;
    longitudeField.value = lng;
  }

  $(document).ready(function() {
    // Hide accordion content by default
    $('.accordion-content').hide();

    $('.accordion-item .accordion-title').click(function() {
      // Toggle active class to show/hide accordion content
      $(this).parent().toggleClass('active');
      $(this).parent().find('.accordion-content').slideToggle();
      $('.accordion-item').not($(this).parent()).removeClass('active').find('.accordion-content').slideUp();

      // Prevent checkbox from being checked/unchecked when clicking on label
      var checkbox = $(this).siblings('input[type="checkbox"]');
      checkbox.prop('checked', !checkbox.prop('checked'));
    });

    // Event handler for "All {location name}" checkbox
    $('.venues-checkbox').change(function() {
      var isChecked = $(this).prop('checked');
      var accordionContent = $(this).closest('.accordion-item').find('.accordion-content');
      accordionContent.find('.venue-checkbox').prop('checked', isChecked);
    });
  });
</script>
