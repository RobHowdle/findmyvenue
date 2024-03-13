<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">Venues</h1>
  </x-slot>

  <x-venue-table :venues="$venues" :genres="$genres">
    @forelse ($venues as $venue)
      <tr class="border-b odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-gray-900 even:dark:bg-gray-800">
        <th scope="row" class="whitespace-nowrap px-6 py-4 font-sans text-xl text-white">
          <!-- Venue name -->
          <a href="{{ route('venue', $venue->id) }}" class="venue-link">{{ $venue->name }}</a>
        </th>
        <td class="whitespace-nowrap px-6 py-4 font-sans text-xl text-white">
          <!-- Location -->
          {{ $venue->postal_town }}
        </td>
        <td class="flex gap-4 whitespace-nowrap px-6 py-4 font-sans text-xl text-white">
          <!-- Contact links -->
          @if ($venue->contact_number)
            <a href="tel:{{ $venue->contact_number }}"><span class="fas fa-phone"></span></a>
          @endif
          @if ($venue->contact_email)
            <a href="mailto:{{ $venue->contact_email }}"><span class="fas fa-envelope"></span></a>
          @endif
          <!-- Additional processing for contact links -->
          @if ($venue->platforms)
            @foreach ($venue->platforms as $platform)
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
        <td class="whitespace-nowrap px-6 py-4 font-sans text-xl text-white">
          <!-- Promoter names -->
          @if ($venue->promoters)
            @foreach ($venue->promoters as $promoter)
              <a href="{{ url('promoters', $promoter->id) }}">{{ $promoter['name'] }}</a>
            @endforeach
          @endif
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="4" class="text-center text-2xl text-white">No venues found</td>
      </tr>
    @endforelse
  </x-venue-table>
</x-guest-layout>
<script>
  $(document).ready(function() {
    // Close modal click event
    $(document).on("click", ".close", function() {
      closeModal();
    });

    // Close modal on escape key press
    $(document).keydown(function(event) {
      if (event.keyCode === 27 && $(".modal").hasClass("modal-visible")) {
        closeModal();
      }
    });

    // Accordion functionality
    $("[data-accordion-target]").click(function() {
      const isExpanded = $(this).attr("aria-expanded") === "true";
      const accordionBody = $(this).attr("data-accordion-target");

      if (isExpanded) {
        $(this).attr("aria-expanded", "false");
        $(accordionBody).addClass("hidden");
      } else {
        $(this).attr("aria-expanded", "true");
        $(accordionBody).removeClass("hidden");
      }
    });
  });

  // Function to close modal
  function closeModal() {
    $(".modal").removeClass("modal-visible");
  }

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
    });

    // Event handler for "All Genres" checkbox
    $("#all-genres").change(function() {
      var isChecked = $(this).prop("checked");
      $(".genre-checkbox").prop("checked", isChecked);

      // If "All Genres" checkbox is checked, select all subgenres of each genre
      if (isChecked) {
        $(".accordion-item .subgenre-checkbox").prop("checked", false); // Uncheck subgenres
      }
    });
  });

  // Attach event listener for filter checkboxes
  $('.filter-checkbox').change(function() {
    applyFilters();
  });

  // Attach event listener for search input
  $('#search-input').on('input', function() {
    applyFilters();
  });

  // Attach event listener for genre checkboxes
  $('.genre-checkbox').change(function() {
    // If "All Genres" checkbox is selected, deselect all individual genre checkboxes
    if ($(this).prop('id') === 'all-genres') {
      $('.genre-checkbox').not(this).prop('checked', false);
    } else {
      // If an individual genre checkbox is selected, deselect the "All Genres" checkbox
      $('#all-genres').prop('checked', false);
    }
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

    // Get search query
    var searchQuery = $('#search-input').val();

    // Send AJAX request to fetch filtered data
    $.ajax({
      url: '/venues/filter',
      method: 'GET',
      data: {
        location: searchQuery,
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

  // Updates the table after filter
  function updateTable(data) {
    // Clear existing table content
    $('#venues tbody').empty();

    // Check if data is not null or empty array
    if (data && data.length > 0) {
      // Append new rows based on filtered data
      data.forEach(function(venue) {
        var rowHtml = `
                <tr class="border-b odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-gray-900 even:dark:bg-gray-800">
                    <th scope="row" class="whitespace-nowrap px-6 py-4 font-sans text-xl text-white">
                        ${venue.name}
                    </th>
                    <td class="whitespace-nowrap px-6 py-4 font-sans text-xl text-white">
                        ${venue.location}
                    </td>
                    <td class="flex gap-4 whitespace-nowrap px-6 py-4 font-sans text-xl text-white">
                        <!-- Contact links -->
                        ${venue.contact_number ? '<a href="tel:' + venue.contact_number + '"><span class="fas fa-phone"></span></a>' : ''}
                        ${venue.contact_email ? '<a href="mailto:' + venue.contact_email + '"><span class="fas fa-envelope"></span></a>' : ''}
                        <!-- Additional processing for contact links -->
                        ${venue.platforms ? venue.platforms.map(function(platform) {
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
                    <td class="whitespace-nowrap px-6 py-4 font-sans text-xl text-white">
                        <!-- Promoter names -->
                        ${venue.promoters ? venue.promoters.map(function(promoter) {
                            return '<a href="' + promoter.url + '">' + promoter.name + '</a>';
                        }).join('') : ''}
                    </td>
                </tr>
            `;
        $('#venues tbody').append(rowHtml);
      });
    } else {
      // Display message if no venues found
      var noVenuesRow = `
            <tr>
                <td colspan="4" class="text-center text-2xl text-white">No venues found</td>
            </tr>
        `;
      $('#venues tbody').append(noVenuesRow);
    }
  }

  // Event handler for "All Types" checkbox
  $("#all-bands").change(function() {
    var isChecked = $(this).prop("checked");
    $(".filter-checkbox").prop("checked", isChecked);
  });
</script>
