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
            class="{{ $promoters ? 'md:block' : 'hidden' }} whitespace-nowrap px-2 py-2 font-sans text-white md:px-6 md:py-3 md:text-base lg:px-8 lg:py-4 lg:text-lg">
            @foreach ($venue->promoters as $promoter)
              <a class="hover:text-yns_yellow" href="{{ url('promoters', $promoter->id) }}">{{ $promoter['name'] }}</a>
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
<script
  src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initialize"
  async defer></script>
<script>
  // Search Bar
  function initialize() {
    jQuery('form').on('keyup keypress', function(e) {
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
      const isEdit = document.getElementById(fieldKey + "-latitude").value != '' && document.getElementById(fieldKey +
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
  jQuery('.filter-checkbox').change(function() {
    applyFilters();
  });

  // Attach event listener for search input
  jQuery('#address-input').on('input', function() {
    applyFilters();
  });

  // Event handler for "All Types" checkbox
  jQuery("#all-bands").change(function() {
    var isChecked = jQuery(this).prop("checked");
    jQuery(".filter-checkbox").prop("checked", isChecked);
  });

  // Event handler for "All Genres" checkbox
  jQuery("#all-genres").change(function() {
    var isChecked = jQuery(this).prop("checked");
    jQuery(".genre-checkbox").prop("checked", isChecked);

    // If "All Genres" checkbox is checked, select all subgenres of each genre
    if (isChecked) {
      jQuery(".accordion-item .subgenre-checkbox").prop("checked", true); // Uncheck subgenres
    }
    applyFilters();
  })

  // Attach event listener for genre checkboxes
  jQuery('.genre-checkbox').change(function() {
    var isChecked = jQuery(this).prop('checked');
    var genreId = jQuery(this).attr('id');

    var genreIdParts = genreId.split('-');
    var genreIndex = genreIdParts[2];

    var subgenreCheckboxes = jQuery('input[type="checkbox"][id*="genre-' + genreIndex + '-subgenre"]');

    subgenreCheckboxes.prop('checked', isChecked);

    applyFilters();
  });

  // Attach event listener for subgenre checkboxes
  jQuery('.subgenre-checkbox').change(function() {
    // If a subgenre checkbox is selected, deselect the "All Genres" checkbox
    jQuery('#all-genres').prop('checked', false);
    applyFilters();
  });

  function applyFilters() {
    // Get selected filter values
    var bandTypeValue = [];
    var allTypesSelected = false;
    var searchQuery = jQuery('#address-input').val();
    jQuery('.filter-checkbox:checked').each(function() {
      var filterValue = jQuery(this).val();

      // Check if "All Types" is selected
      if (filterValue === 'all') {
        // If "All Types" is selected, populate the array with all individual values
        allTypesSelected = true;
        return false;
      }

      // If "All Types" is not selected and it was selected before, clear the array
      if (allTypesSelected) {
        bandTypeValue = [];
        allTypesSelected = false; // Reset the flag
      }

      // Push the filter value into the array
      bandTypeValue.push(filterValue);
    });

    // If "All Types" is selected, populate the array with all individual values
    if (allTypesSelected) {
      jQuery('.filter-checkbox').each(function() {
        var filterValue = jQuery(this).val();
        if (filterValue !== 'all') {
          bandTypeValue.push(filterValue);
        }
      });
    }

    var selectedGenres = [];
    jQuery('.genre-checkbox:checked').each(function() {
      selectedGenres.push(jQuery(this).val());
    });

    var selectedSubgenres = [];
    jQuery('.subgenre-checkbox:checked').each(function() {
      selectedSubgenres.push(jQuery(this).val());
    });

    var mergedGenres = selectedGenres.concat(selectedSubgenres)

    // Send AJAX request to fetch filtered data
    $.ajax({
      url: '/venues/filter',
      method: 'GET',
      data: {
        search_query: searchQuery,
        band_type: bandTypeValue,
        genres: mergedGenres,
      },
      success: function(data) {
        // Update table with filtered data
        updateTable(data);
      },
      error: function(err) {
        console.error('Error applying filters:', err);
      }
    });
  }

  function updateVenuesTable(filteredVenues) {
    if (!Array.isArray(filteredVenues)) {
      console.error("filteredVenues is not an array:", filteredVenues);
      return;
    }

    // Generate HTML for the filtered venues
    var rowsHtml = filteredVenues.map(function(venue) {
      var venueRoute = "{{ route('venue', ':venueId') }}";
      var ratingHtml = getRatingHtml(venue.average_rating); // Function to generate rating HTML
      var promotersCount = {{ $venuePromoterCount }};
      const className = promotersCount > 0 ? 'md:block' : 'hidden';

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
                <td class="hidden whitespace-nowrap align-middle text-white px-2 py-2 md:block md:px-6 md:py-3 md:text-base lg:px-8 lg:py-4 lg:text-lg">
                    ${venue.contact_number ? '<a href="tel:' + venue.contact_number + '" class="hover:text-yns_yellow"><span class="fas fa-phone"></span></a>' : ''}
                    ${venue.contact_email ? '<a href="mailto:' + venue.contact_email + '" class="hover:text-yns_yellow"><span class="fas fa-envelope"></span></a>' : ''}
                    ${venue.platforms ? venue.platforms.map(function(platform) {
                        switch (platform.platform) {
                            case 'facebook':
                                return '<a class="hover:text-yns_yellow" href="' + platform.url + '" target=_blank><span class="fab fa-facebook"></span></a>';
                            case 'twitter':
                                return '<a class="hover:text-yns_yellow" href="' + platform.url + '" target=_blank><span class="fab fa-twitter"></span></a>';
                            case 'instagram':
                                return '<a class="hover:text-yns_yellow" href="' + platform.url + '" target=_blank><span class="fab fa-instagram"></span></a>';
                            case 'snapchat':
                                return '<a class="hover:text-yns_yellow" href="' + platform.url + '" target=_blank><span class="fab fa-snapchat-ghost"></span></a>';
                            case 'tiktok':
                                return '<a class="hover:text-yns_yellow" href="' + platform.url + '" target=_blank><span class="fab fa-tiktok"></span></a>';
                            case 'youtube':
                                return '<a class="hover:text-yns_yellow" href="' + platform.url + '" target=_blank><span class="fab fa-youtube"></span></a>';
                            default:
                                return '';
                        }
                    }).join('') : ''}
                </td>
                <td class="whitespace-nowrap ${className} font-sans  text-white px-2 py-2 md:px-6 md:py-3 md:text-base lg:px-8 lg:py-4 lg:text-lg">
                    ${venue.promoters ? venue.promoters.map(function(promoter) {
                        return '<a href="' + promoter.url + '">' + promoter.name + '</a>';
                    }).join('') : ''}
                </td>
            </tr>
        `;
    }).join('');

    // Replace the existing HTML content with the new HTML
    jQuery('#venues tbody').html(rowsHtml);
  }

  // Function to generate HTML for the rating
  function getRatingHtml(rating) {
    if (rating === undefined || rating === null) return ''; // Return empty if no rating

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

  // Update the updateTable function to pass the filtered venues to updateVenuesTable
  function updateTable(data) {
    var venues = data.venues;
    var pagination = data.pagination;

    // Check if data is not null or empty array
    if (data.venues && data.venues.length > 0) {
      // Append new rows based on filtered data
      updateVenuesTable(data.venues);
    } else {
      // Display message if no venues found
      var noVenuesRow = `
            <tr class="odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-black even:dark:bg-gray-900">
                <td colspan="5" class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4 uppercase text-center">No venues found</td>
            </tr>
        `;
      jQuery('#venues tbody').html(noVenuesRow);
    }
  }

  function setLocationCoordinates(key, lat, lng) {
    const latitudeField = document.getElementById(key + "-" + "latitude");
    const longitudeField = document.getElementById(key + "-" + "longitude");
    latitudeField.value = lat;
    longitudeField.value = lng;
  }
</script>
