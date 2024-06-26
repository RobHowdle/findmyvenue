<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">Other - {{ $otherTitle->service_name }}</h1>
  </x-slot>

  <x-photographer-table :photographers="$photographers" :envType="$envTyp" :workTimes="workTimes">
    @forelse ($promoters as $promoter)
      <tr class="border-b odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-black even:dark:bg-gray-900">
        <td
          class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
          <a href="{{ route('promoter', $promoter->id) }}"
            class="promoter-link hover:text-gray-400">{{ $promoter->name }}</a>
        </td>
        <td class="rating-wrapper flex whitespace-nowrap px-6 py-4 font-sans text-xl text-white">

        </td>
        <td
          class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
          {{ $promoter->postal_town }}
        </td>
        <td
          class="flex gap-4 whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
          @if ($promoter->contact_number)
            <a class="hover:text-gray-400" href="tel:{{ $promoter->contact_number }}"><span
                class="fas fa-phone"></span></a>
          @endif
          @if ($promoter->contact_email)
            <a class="hover:text-gray-400" href="mailto:{{ $promoter->contact_email }}"><span
                class="fas fa-envelope"></span></a>
          @endif
          @if ($promoter->platforms)
            @foreach ($promoter->platforms as $platform)
              @if ($platform['platform'] == 'facebook')
                <a href="{{ $platform['url'] }}" target=_blank><span class="fab fa-facebook"></span></a>
              @elseif($platform['platform'] == 'twitter')
                <a href="{{ $platform['url'] }}" target=_blank><span class="fab fa-twitter"></span></a>
              @elseif($platform['platform'] == 'instagram')
                <a href="{{ $platform['url'] }}" target=_blank><span class="fab fa-instagram"></span></a>
              @elseif($platform['platform'] == 'snapchat')
                <a href="{{ $platform['url'] }}" target=_blank><span class="fab fa-snapchat-ghost"></span></a>
              @elseif($platform['platform'] == 'tiktok')
                <a href="{{ $platform['url'] }}" target=_blank><span class="fab fa-tiktok"></span></a>
              @elseif($platform['platform'] == 'youtube')
                <a href="{{ $platform['url'] }}" target=_blank><span class="fab fa-youtube"></span></a>
              @endif
            @endforeach
          @endif
        </td>
        <td
          class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
          @if ($promoter->venues)
            @foreach ($promoter->venues as $venue)
              <a class="hover:text-gray-400"
                href="{{ url('venues', $venue->id) }}">{{ $venue->name }}</a>{{ !$loop->last ? ', ' : '' }}
            @endforeach
          @endif
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="4" class="text-center text-2xl text-white">No promoters found</td>
      </tr>
    @endforelse
    </x-promoters-table>
</x-guest-layout>
<script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMjlXwDOk74oMDPgOp4YWdWxPa5xtHGA&libraries=places&callback=initialize"
  async defer></script>
<script>
  $(document).ready(function() {
    // Accordion functionality
    $("[data-accordion-target]").click(function() {
      const isExpanded = $(this).attr("aria-expanded") === "true";
      const accordionBody = $(this).attr("data-accordion-target");

      $(this).find('svg').toggleClass('rotate-180');

      if (isExpanded) {
        $(this).attr("aria-expanded", "false");
        $(accordionBody).slideUp().addClass("hidden");
      } else {
        $(accordionBody).slideDown().removeClass("hidden");
        $(this).attr("aria-expanded", "true");
      }
    });
  });

  $(document).ready(function() {
    // Hide accordion content by default
    $(".accordion-content").hide();

    $(".accordion-item .accordion-title").click(function() {
      // Toggle active class to show/hide accordion content
      $(this).parent().toggleClass("active");
      $(this).parent().find(".accordion-content").slideToggle();
      $(".accordion-item")
        .not($(this).parent())
        .removeClass("active")
        .find(".accordion-content")
        .slideUp();

      // Prevent checkbox from being checked/unchecked when clicking on label
      var checkbox = $(this).siblings('input[type="checkbox"]');
      checkbox.prop("checked", !checkbox.prop("checked"));
    });;
  });

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
  $('.filter-checkbox').change(function() {
    applyFilters();
  });

  // Attach event listener for search input
  $('#address-input').on('input', function() {
    applyFilters();
  });

  // Event handler for "All Types" checkbox
  $("#all-bands").change(function() {
    var isChecked = $(this).prop("checked");
    $(".filter-checkbox").prop("checked", isChecked);
  });

  // Event handler for "All Genres" checkbox
  $("#all-genres").change(function() {
    var isChecked = $(this).prop("checked");
    $(".genre-checkbox").prop("checked", isChecked);

    // If "All Genres" checkbox is checked, select all subgenres of each genre
    if (isChecked) {
      $(".accordion-item .subgenre-checkbox").prop("checked", true); // Uncheck subgenres
    }
    applyFilters();
  })

  // Attach event listener for genre checkboxes
  $('.genre-checkbox').change(function() {
    var isChecked = $(this).prop('checked');
    var genreId = $(this).attr('id');

    var genreIdParts = genreId.split('-');
    var genreIndex = genreIdParts[2];

    var subgenreCheckboxes = $('input[type="checkbox"][id*="genre-' + genreIndex + '-subgenre"]');

    subgenreCheckboxes.prop('checked', isChecked);

    applyFilters();
  });


  // Attach event listener for subgenre checkboxes
  $('.subgenre-checkbox').change(function() {
    // If a subgenre checkbox is selected, deselect the "All Genres" checkbox
    $('#all-genres').prop('checked', false);
    applyFilters();
  });

  function applyFilters() {
    // Get selected filter values
    var bandTypeValue = [];
    var allTypesSelected = false;
    var searchQuery = $('#address-input').val();
    $('.filter-checkbox:checked').each(function() {
      var filterValue = $(this).val();

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
      $('.filter-checkbox').each(function() {
        var filterValue = $(this).val();
        if (filterValue !== 'all') {
          bandTypeValue.push(filterValue);
        }
      });
    }

    var selectedGenres = [];
    $('.genre-checkbox:checked').each(function() {
      selectedGenres.push($(this).val());
    });

    var selectedSubgenres = [];
    $('.subgenre-checkbox:checked').each(function() {
      selectedSubgenres.push($(this).val());
    });

    var mergedGenres = selectedGenres.concat(selectedSubgenres)

    // Send AJAX request to fetch filtered data

    $.ajax({
      url: '/promoters/filter',
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

  // Define the updatepromotersTable function outside of the updateTable function
  function updatepromotersTable(filteredpromoters) {
    // Generate HTML for the filtered promoters
    var rowsHtml = filteredpromoters.map(function(promoter) {
      var promoterRoute = "{{ route('promoter', ':promoterId') }}";
      return `
            <tr class="border-b odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-black even:dark:bg-gray-900">
                <th scope="row" class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
                    <a href="${promoterRoute.replace(':promoterId', promoter.id)}" class="promoter-link hover:text-gray-400">${promoter.name}</a>
                </th>
                <td class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
                    ${promoter.postal_town}
                </td>
                <td class="flex gap-4 whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
                    <!-- Contact links -->
                    ${promoter.contact_number ? '<a href="tel:' + promoter.contact_number + '"><span class="fas fa-phone"></span></a>' : ''}
                    ${promoter.contact_email ? '<a href="mailto:' + promoter.contact_email + '"><span class="fas fa-envelope"></span></a>' : ''}
                    <!-- Additional processing for contact links -->
                    ${promoter.platforms ? promoter.platforms.map(function(platform) {
                        switch (platform.platform) {
                            case 'facebook':
                                return '<a href="' + platform.url + '" target=_blank><span class="fab fa-facebook"></span></a>';
                            case 'twitter':
                                return '<a href="' + platform.url + '" target=_blank><span class="fab fa-twitter"></span></a>';
                            case 'instagram':
                                return '<a href="' + platform.url + '" target=_blank><span class="fab fa-instagram"></span></a>';
                            case 'snapchat':
                                return '<a href="' + platform.url + '" target=_blank><span class="fab fa-snapchat-ghost"></span></a>';
                            case 'tiktok':
                                return '<a href="' + platform.url + '" target=_blank><span class="fab fa-tiktok"></span></a>';
                            case 'youtube':
                                return '<a href="' + platform.url + '" target=_blank><span class="fab fa-youtube"></span></a>';
                            default:
                                return '';
                        }
                    }).join('') : ''}
                </td>
                <td class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
                    <!-- Promoter names -->
                    ${promoter.promoters ? promoter.promoters.map(function(promoter) {
                        return '<a href="' + promoter.url + '">' + promoter.name + '</a>';
                    }).join('') : ''}
                </td>
            </tr>
        `;
    }).join('');

    // Replace the existing HTML content with the new HTML
    $('#promoters tbody').html(rowsHtml);
  }

  // Update the updateTable function to pass the filtered promoters to updatepromotersTable
  function updateTable(data) {
    var promoters = data.promoters;
    var pagination = data.pagination;

    // Check if data is not null or empty array
    if (data.promoters && data.promoters.length > 0) {
      // Append new rows based on filtered data
      updatepromotersTable(data.promoters);
    } else {
      // Display message if no promoters found
      var nopromotersRow = `
            <tr>
                <td colspan="4" class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4 uppercase text-center">No promoters found</td>
            </tr>
        `;
      $('#promoters tbody').html(nopromotersRow);
    }
  }


  function setLocationCoordinates(key, lat, lng) {
    const latitudeField = document.getElementById(key + "-" + "latitude");
    const longitudeField = document.getElementById(key + "-" + "longitude");
    latitudeField.value = lat;
    longitudeField.value = lng;
  }
</script>
