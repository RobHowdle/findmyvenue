<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">Venues</h1>
  </x-slot>

  <x-venue-table :venues="$venues" :genres="$genres" :venuePromoterCount="$venuePromoterCount">
    @forelse ($venues as $venue)
      <tr class="odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-black even:dark:bg-gray-900">
        <th scope="row"
          class="whitespace-nowrap px-2 py-2 font-sans text-white md:px-6 md:py-3 md:text-base lg:px-8 lg:py-4 lg:text-lg">
          <a href="{{ route('venue', $venue->id) }}" class="venue-link hover:text-yns_yellow">{{ $venue->name }}</a>
        </th>
        <td
          class="rating-wrapper px:2 py:2 hidden whitespace-nowrap sm:text-base md:px-6 md:py-3 lg:flex lg:px-8 lg:py-4">
          {!! $overallReviews[$venue->id] !!}
        </td>
        <td
          class="whitespace-nowrap px-2 py-2 font-sans text-white md:px-6 md:py-3 md:text-base lg:px-8 lg:py-4 lg:text-lg">
          {{ $venue->postal_town }}
        </td>
        <td
          class="hidden whitespace-nowrap px-2 py-2 align-middle text-white md:block md:px-6 md:py-3 md:text-base lg:px-8 lg:py-4 lg:text-lg">
          <x-contact-and-social-links :item="$venue" />
        </td>
        @if ($venuePromoterCount != 0)
          <td
            class="{{ $venues ? 'md:block' : 'hidden' }} whitespace-nowrap px-2 py-2 font-sans text-white md:px-6 md:py-3 md:text-base lg:px-8 lg:py-4 lg:text-lg">
            @foreach ($venue->venues as $venue)
              <a class="hover:text-yns_yellow" href="{{ url('venues', $venue->id) }}">{{ $venue['name'] }}</a>
            @endforeach
          </td>
        @endif
      </tr>
    @empty
      <tr class="border-b odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-black even:dark:bg-gray-900">
        <td colspan="4" class="text-center text-2xl text-white dark:bg-gray-900">No venues found</td>
      </tr>
    @endforelse
  </x-venue-table>
</x-guest-layout>
<script>
  $(document).ready(function() {
    // Search Bar
    function initialize() {
      $('form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
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

    // Attach event listener for filter checkboxes
    $('.filter-checkbox').change(function() {
      applyFilters();
    });

    // Attach event listener for search input
    let debounceTimeout;
    $('#address-input').on('input', function() {
      clearTimeout(debounceTimeout);
      debounceTimeout = setTimeout(() => {
        applyFilters();
      }, 300); // Adjust debounce delay as needed
    });

    // Event handler for "All Types" checkbox
    $("#all-bands").change(function() {
      var isChecked = $(this).prop("checked");
      $(".filter-checkbox").prop("checked", isChecked);
      applyFilters();
    });

    $("#all-genres").change(function() {
      var isChecked = $(this).prop("checked");
      $(".genre-checkbox, .subgenre-checkbox").prop("checked", isChecked);
      applyFilters();
    });

    $('.genre-checkbox').change(function() {
      var genreId = $(this).data('genre-id');
      var isChecked = $(this).prop('checked');
      $(`.subgenre-checkbox[data-genre-id="${genreId}"]`).prop('checked', isChecked);

      // Uncheck "All Genres" if a single genre is deselected
      if (!isChecked) {
        $('#all-genres').prop('checked', false);
      }
      applyFilters();
    });

    $('.subgenre-checkbox').change(function() {
      // Uncheck "All Genres" if any subgenre is deselected
      $('#all-genres').prop('checked', false);
      applyFilters();
    });

    function applyFilters() {
      var bandTypeValue = [];
      var selectedGenres = [];
      var selectedSubgenres = [];
      var searchQuery = $('#address-input').val();

      // Collect selected band types
      $('.filter-checkbox:checked').each(function() {
        bandTypeValue.push($(this).val());
      });

      // Collect selected genres
      $('.genre-checkbox:checked').each(function() {
        selectedGenres.push($(this).val());
      });

      // Collect selected subgenres
      $('.subgenre-checkbox:checked').each(function() {
        selectedSubgenres.push($(this).val());
      });

      // Combine genres and subgenres
      var mergedGenres = [...selectedGenres, ...selectedSubgenres];

      // Send AJAX request
      $.ajax({
        url: '/venues/filter',
        method: 'POST',
        data: {
          _token: $('meta[name="csrf-token"]').attr('content'),
          search_query: searchQuery,
          band_type: bandTypeValue,
          genres: mergedGenres,
        },
        success: function(data) {
          // Extract venues array and pass it to the function
          if (data.venues && Array.isArray(data.venues)) {
            updateTable(data);
          } else {
            console.error("The 'venues' field is not an array or is missing:", data.venues);
          }
        },
        error: function(err) {
          console.error('Error applying filters:', err);
        }
      });

    }

    // Define the updatevenuesTable function outside of the updateTable function
    function updateResultsTable(filteredPromoters) {
      if (!Array.isArray(filteredPromoters)) {
        console.error("filteredPromoters is not an array:", filteredPromoters);
        return;
      }
      // Generate HTML for the filtered venues
      var rowsHtml = filteredPromoters.map(function(venue) {
        var venueRoute = "{{ route('venue', ':venueId') }}";
        var ratingHtml = getRatingHtml(venue.average_rating);
        var venuePromoterCount = {{ $venuePromoterCount }};
        const className = venuePromoterCount > 0 ? 'md:block' : 'hidden';
        return `
            <tr class="odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-black even:dark:bg-gray-900">
                <th scope="row" class="whitespace-nowrap font-sans text-white px-2 py-2 md:px-6 md:py-3 md:text-base lg:px-8 lg:py-4 lg:text-lg">
                    <a href="${venueRoute.replace(':venueId', venue.id)}" class="venue-link hover:text-yns_yellow">${venue.name}</a>
                </th>
                <td class="rating-wrapper hidden whitespace-nowrap px-2 py-2 sm:text-base md:px-6 md:py-3 lg:flex lg:px-8 lg:py-4">
                    ${ratingHtml}
                </td>
                <td class="whitespace-nowrap font-sans text-white px-2 py-2 md:px-6 md:py-3 md:text-base lg:px-8 lg:py-4 lg:text-lg">
                    ${venue.postal_town}
                </td>
                <td class="hidden whitespace-nowrap px-2 py-2 align-middle text-white md:block md:px-6 md:py-3 md:text-base lg:px-8 lg:py-4 lg:text-lg">
                    ${venue.contact_number ? '<a href="tel:' + venue.contact_number + '" class="hover:text-yns_yellow mr-2"><span class="fas fa-phone"></span></a>' : ''}
                    ${venue.contact_email ? '<a href="mailto:' + venue.contact_email + '" class="hover:text-yns_yellow mr-2><span class="fas fa-envelope"></span></a>' : ''}
                    ${venue.platforms ? venue.platforms.map(function(platform) {
                        switch (platform.platform) {
                            case 'facebook':
                                return '<a class="hover:text-yns_yellow mr-2" href="' + platform.url + '" target=_blank><span class="fab fa-facebook"></span></a>';
                            case 'twitter':
                                return '<a class="hover:text-yns_yellow mr-2" href="' + platform.url + '" target=_blank><span class="fab fa-twitter"></span></a>';
                            case 'instagram':
                                return '<a class="hover:text-yns_yellow mr-2" href="' + platform.url + '" target=_blank><span class="fab fa-instagram"></span></a>';
                            case 'snapchat':
                                return '<a class="hover:text-yns_yellow mr-2" href="' + platform.url + '" target=_blank><span class="fab fa-snapchat-ghost"></span></a>';
                            case 'tiktok':
                                return '<a class="hover:text-yns_yellow mr-2" href="' + platform.url + '" target=_blank><span class="fab fa-tiktok"></span></a>';
                            case 'youtube':
                                return '<a class="hover:text-yns_yellow mr-2" href="' + platform.url + '" target=_blank><span class="fab fa-youtube"></span></a>';
                            case 'bluesky':
                                return '<a class="hover:text-yns_yellow mr-2" href="' + platform.url + '" target=_blank><span class="fa-brands fa-bluesky"></span></a>';
                            default:
                                return '';
                        }
                    }).join('') : ''}
                </td>
                <td class="whitespace-nowrap ${className} font-sans  text-white px-2 py-2 md:px-6 md:py-3 md:text-base lg:px-8 lg:py-4 lg:text-lg">
                    ${venue.promoter ? venue.promoter.map(function(venue) {
                        return '<a href="' + venue.url + '">' + venue.name + '</a>';
                    }).join('') : ''}
                </td>
            </tr>
        `;
      }).join('');
      // Replace the existing HTML content with the new HTML
      $('#venues tbody').html(rowsHtml);
    }

    // Function to generate HTML for the rating
    function getRatingHtml(rating) {
      if (rating === undefined || rating === null) return '';

      var ratingHtml = '';
      var totalIcons = 5;
      var fullIcons = Math.floor(rating);
      var fraction = rating - fullIcons;
      var emptyIcon = "{{ asset('storage/images/system/ratings/empty.png') }}";
      var fullIcon = "{{ asset('storage/images/system/ratings/full.png') }}";
      var hotIcon = "{{ asset('storage/images/system/ratings/hot.png') }}";

      // Special case: all icons are hot
      if (rating === totalIcons) {
        ratingHtml = Array(totalIcons).fill('<img src="' + hotIcon + '" alt="Hot Icon" />').join('');
      } else {
        // Add full icons
        for (var i = 0; i < fullIcons; i++) {
          ratingHtml += '<img src="' + fullIcon + '" alt="Full Icon" />';
        }

        // Handle the fractional icon
        if (fraction > 0) {
          ratingHtml += '<div class="partially-filled-icon" style="width: ' + (fraction * 48) +
            'px; overflow: hidden; display:inline-block;">' +
            '<img src="' + fullIcon + '" alt="Partial Full Icon" />' +
            '</div>';
        }

        // Add empty icons to fill the rest
        var iconsDisplayed = fullIcons + (fraction > 0 ? 1 : 0);
        var remainingIcons = totalIcons - iconsDisplayed;

        for (var j = 0; j < remainingIcons; j++) {
          ratingHtml += '<img src="' + emptyIcon + '" alt="Empty Icon" />';
        }
      }

      return ratingHtml;
    }

    // Update the updateTable function to pass the filtered venues to updatevenuesTable
    function updateTable(data) {
      var venues = data.venues;
      var pagination = data.pagination;

      // Check if data is not null or empty array
      if (data.venues && data.venues.length > 0) {
        // Append new rows based on filtered data
        updateResultsTable(data.venues);
      } else {
        // Display message if no venues found
        var noPromotersRow = `
            <tr class="odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-black even:dark:bg-gray-900">
                <td colspan="5" class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4 uppercase text-center">No venues found</td>
            </tr>
        `;
        $('#venues tbody').html(noPromotersRow);
      }
    }

    function setLocationCoordinates(key, lat, lng) {
      const latitudeField = document.getElementById(key + "-" + "latitude");
      const longitudeField = document.getElementById(key + "-" + "longitude");
      latitudeField.value = lat;
      longitudeField.value = lng;
    }
  });
</script>
