<header>
  <h2 class="text-md font-heading font-medium text-white">
    {{ __('The genres and band types you work with') }}
  </h2>
</header>

<!-- Removed the form wrapper -->
<x-input-label>Select your genres</x-input-label>
<div class="grid sm:grid-cols-2 sm:gap-3 lg:grid-cols-3 lg:gap-4">
  @php
    $promoterGenres = is_string($promoter->genre) ? json_decode($promoter->genre, true) : $promoter->genre;
    $bandGenres = is_string($promoter->band_type) ? json_decode($promoter->band_type, true) : $promoter->band_type;
  @endphp

  <!-- "All Genres" checkbox -->
  <div class="flex items-center">
    <input id="all-genres" name="all-genres" type="checkbox" value=""
      class="genre-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
      {{ $isAllGenres ? 'checked' : '' }}>
    <label for="all-genres" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">All Genres</label>
  </div>


  <!-- Genres Accordion -->
  @foreach ($genres as $index => $genre)
    <div class="border-b border-slate-200">
      <button onclick="toggleAccordion({{ $index }})" id="genre-{{ $index }}"
        class="accordion-btn flex w-full items-center justify-between py-5 text-white">
        <span class="genre-name">{{ $genre['name'] }}</span>
        <span class="check-status mr-4"></span>
        <span id="icon-{{ $index }}" class="text-slate-800 transition-transform duration-300">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#ffffff" class="h-4 w-4">
            <path
              d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z" />
          </svg>
        </span>
      </button>
      <div id="subgenres-accordion-{{ $index }}"
        class="max-h-0 content grid grid-cols-2 overflow-hidden transition-all duration-300 ease-in-out">
        <div class="all-genre-wrapper flex items-center gap-2 pb-2 text-sm text-white">
          <x-input-checkbox class="genre-checkbox" id="all-{{ strtolower($genre['name']) }}-{{ $index }}"
            onclick="toggleSubgenresCheckboxes({{ $index }})" data-all="true"
            name="all-{{ $genre['name'] }}-{{ $index }}" data-genre="{{ $genre['name'] }}"
            value="all-{{ strtolower($genre['name']) }}">
          </x-input-checkbox>
          <x-input-label>All {{ $genre['name'] }}</x-input-label>
        </div>

        @foreach ($genre['subgenres'] as $subIndex => $subgenre)
          @php
            $subgenreSlug = strtolower(str_replace(' ', '_', $subgenre));
          @endphp
          <div class="subgenre-wrapper flex items-center gap-2 pb-2 text-sm text-white">
            <x-input-checkbox class="subgenre-checkbox" id="subgenre-{{ $subgenreSlug }}"
              name="subgenre-{{ $subgenreSlug }}" data-parent="{{ $genre['name'] }}"
              value="{{ $subgenreSlug }}"></x-input-checkbox>
            <x-input-label>{{ $subgenre }}</x-input-label>
          </div>
        @endforeach
      </div>
    </div>
  @endforeach
</div>

<x-input-label-dark class="mt-8">Select your band types</x-input-label-dark>
<div class="grid sm:grid-cols-2 sm:gap-3 lg:grid-cols-3 lg:gap-4">
  <div class="flex items-center">
    <input id="all-bands" name="band_type[]" type="checkbox" value="all"
      class="filter-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800" />
    <label for="all-bands" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">All
      Types</label>
  </div>
  <div class="flex items-center">
    <input id="original-bands" name="original-bands" type="checkbox" value="original-bands"
      class="filter-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800" />
    <label for="original-bands" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Original</label>
  </div>
  <div class="flex items-center">
    <input id="cover-bands" name="cover-bands" type="checkbox" value="cover-bands"
      class="filter-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800" />
    <label for="cover-bands" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Covers</label>
  </div>
  <div class="flex items-center">
    <input id="tribute-bands" name="tribute-bands" type="checkbox" value="tribute-bands"
      class="filter-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800" />
    <label for="tribute-bands" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tributes</label>
  </div>
</div>

<script defer>
  const genres = @json($genres);
  const dashboardType = "{{ $dashboardType }}";
  let promoterGenres = @json($promoterData['promoterGenres']);

  document.addEventListener("DOMContentLoaded", () => {
    preCheckCheckboxes(promoterGenres);
  });

  function preCheckCheckboxes(promoterGenres) {
    Object.entries(promoterGenres).forEach(([genre, data]) => {
      const normalizedGenre = genre.replace(/\s+/g, '_').toLowerCase();
      const index = data.index || 0;

      // Handle "All Genre" checkbox
      const allGenreCheckbox = document.getElementById(`all-${normalizedGenre}-${index}`);
      if (allGenreCheckbox && data.all) {
        allGenreCheckbox.checked = true;
      }

      // Handle subgenres
      const genreButton = document.getElementById(`genre-${index}`);
      const checkStatus = genreButton?.querySelector('.check-status');
      let checkedSubgenresCount = 0;
      const totalSubgenres = data.subgenres ? data.subgenres.length : 0;

      if (Array.isArray(data.subgenres)) {
        data.subgenres.forEach((subgenre) => {
          const subgenreSlug = subgenre.replace(/\s+/g, '_').toLowerCase();
          const subgenreCheckbox = document.querySelector(
            `input[data-parent="${genre}"][value="${subgenreSlug}"]`
          );
          if (subgenreCheckbox) {
            subgenreCheckbox.checked = true;
            checkedSubgenresCount++;
          }
        });
      }

      // Update the accordion button's check status
      if (checkStatus) {
        if (checkedSubgenresCount === totalSubgenres && totalSubgenres > 0) {
          checkStatus.innerHTML = `<i class="check-icon">✔</i>`;
        } else {
          checkStatus.innerHTML = `${checkedSubgenresCount}/${totalSubgenres}`;
        }
      }
    });
  }

  function toggleAccordion(index) {
    const content = document.getElementById(`subgenres-accordion-${index}`);
    const icon = document.getElementById(`icon-${index}`);

    // Check if the content and icon elements exist
    if (!content || !icon) {
      console.error(`Content or Icon not found for index: ${index}`);
      return; // Early exit if elements are not found
    }

    // SVG for Minus icon
    const minusSVG = `
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#ffffff" class="w-4 h-4">
        <path d="M3.75 7.25a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5h-8.5Z" />
      </svg>
    `;

    // SVG for Plus icon
    const plusSVG = `
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#ffffff" class="w-4 h-4">
        <path d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z" />
      </svg>
    `;

    // Toggle the content's max-height for smooth opening and closing
    if (content.style.maxHeight && content.style.maxHeight !== '0px') {
      content.style.maxHeight = '0';
      icon.innerHTML = plusSVG;
    } else {
      content.style.maxHeight = content.scrollHeight + 'px';
      icon.innerHTML = minusSVG;
    }
  }

  function toggleSubgenresCheckboxes(genreIndex) {
    const genre = genres[genreIndex].name;
    const allCheckbox = document.querySelector(`#all-${genre.toLowerCase().replace(/\s+/g, '_')}-${genreIndex}`);
    const subgenreCheckboxes = document.querySelectorAll(`#subgenres-accordion-${genreIndex} .subgenre-checkbox`);

    if (allCheckbox) {
      const isChecked = allCheckbox.checked;

      subgenreCheckboxes.forEach((checkbox) => {
        checkbox.checked = isChecked;
      });
    }
  }

  // Genre checkbox (All Genre) click event
  document.querySelectorAll('.genre-checkbox').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
      if (checkbox && checkbox.dataset) {
        console.log('all checkbox');
        sendCheckedGenres(checkbox);
      } else {
        console.error('Checkbox is invalid or missing dataset attributes:', checkbox);
      }
    });
  });

  document.querySelectorAll('.subgenre-checkbox').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
      const genreName = checkbox.dataset.parent;
      const genreIndex = genres.findIndex(genre => genre.name === genreName);

      // Update "All Subgenres" checkbox state
      const allCheckbox = document.querySelector(
        `#all-${genreName.toLowerCase().replace(/\s+/g, '_')}-${genreIndex}`);
      const subgenreCheckboxes = document.querySelectorAll(
        `#subgenres-accordion-${genreIndex} .subgenre-checkbox`);
      const allChecked = Array.from(subgenreCheckboxes).every(cb => cb.checked);

      if (allCheckbox) {
        allCheckbox.checked = allChecked;
      }
    });
  });

  function sendCheckedGenres(checkbox) {
    if (!checkbox || !checkbox.dataset) {
      console.error('Invalid checkbox or missing dataset:', checkbox);
      return;
    }

    let genre;
    let subgenreValue;

    // Determine if it's "All Genres" or an individual subgenre checkbox
    if (checkbox.dataset.genre) {
      genre = checkbox.dataset.genre; // This is for the "All Genre" checkbox
      subgenreValue = getAllSubgenresForGenre(genre);
    } else if (checkbox.dataset.parent) {
      genre = checkbox.dataset.parent; // This is for individual subgenre checkboxes
      subgenreValue = checkbox.value; // Get the subgenre value from the individual checkbox
    } else {
      return;
    }

    if (!genre) {
      console.error('Invalid genre value:', checkbox);
      return;
    }

    // Initialize genresData globally if not already done
    let genresData = window.genresData || {};

    if (!genresData[genre]) {
      genresData[genre] = {
        all: false, // Default state for the "All" checkbox
        subgenres: [] // Initialize subgenres as an empty array
      };
    }

    // Get the "All Genres" checkbox for this genre
    const allCheckbox = document.getElementById(`all-${genre.toLowerCase().replace(/\s+/g, '_')}`);

    // Handle "All Genres" checkbox logic
    if (checkbox === allCheckbox) {
      if (checkbox.checked) {
        console.log('Checking all subgenres for', genre);

        // Mark "All" as true and add all subgenres
        genresData[genre].all = true;

        // Dynamically fetch subgenres for the genre
        const subgenreCheckboxes = document.querySelectorAll(`input[data-parent="${genre}"]`);
        genresData[genre].subgenres = Array.from(subgenreCheckboxes).map(subgenreCheckbox => subgenreCheckbox.value);

        // Check all individual subgenre checkboxes
        subgenreCheckboxes.forEach(subgenreCheckbox => {
          subgenreCheckbox.checked = true;
        });
      } else {
        console.log('Unchecking all subgenres for', genre);

        // Uncheck "All" and clear subgenres
        genresData[genre].all = false;
        genresData[genre].subgenres = []; // Reset subgenres to an empty array

        // Uncheck all individual subgenre checkboxes
        document.querySelectorAll(`input[data-parent="${genre}"]`).forEach(subgenreCheckbox => {
          subgenreCheckbox.checked = false;
        });
      }
    } else {
      // Handle individual subgenre checkbox logic
      if (checkbox.checked) {
        // Add subgenre if it's not already in the list
        if (subgenreValue && !genresData[genre].subgenres.includes(subgenreValue)) {
          genresData[genre].subgenres.push(subgenreValue);
        }
      } else {
        // Remove subgenre if it's unchecked
        genresData[genre].subgenres = genresData[genre].subgenres.filter(subgenre => subgenre !== subgenreValue);
      }

      // Update "All" checkbox state based on individual subgenres
      const allSubgenres = document.querySelectorAll(`input[data-parent="${genre}"]`);
      const checkedSubgenres = document.querySelectorAll(`input[data-parent="${genre}"]:checked`);
      genresData[genre].all = allSubgenres.length === checkedSubgenres.length;

      // Sync the "All Genres" checkbox
      if (allCheckbox) {
        allCheckbox.checked = genresData[genre].all;
      }
    }

    // Store the updated genresData in the global object
    window.genresData = genresData;

    // Log the updated genresData for debugging
    sendGenresData(genresData);
  }



  // Send the genres data via AJAX
  function sendGenresData(genresData) {
    $.ajax({
      url: `/profile/${dashboardType}/save-genres`, // Replace with your route
      method: 'POST',
      data: {
        genres: genresData, // The data collected above
        _token: '{{ csrf_token() }}' // CSRF token for security
      },
      success: function(response) {
        console.log('Genres data saved successfully:', response);
        showSuccessNotification(response.message);
      },
      error: function(xhr, status, error) {
        console.error('Error saving genres data:', error);
        showFailureNotification(error.message);
      }
    });
  }

  // Add event listener to each subgenre checkbox
  document.querySelectorAll('.subgenre-checkbox').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
      sendCheckedGenres(checkbox); // Trigger the function to update genres data
    });
  });


  // Helper function to get the index of a genre based on its name
  function getGenreIndex(genreName) {
    for (let i = 0; i < genres.length; i++) {
      if (genres[i].name === genreName) {
        return i;
      }
    }
    return 0; // Default index if not found
  }

  // Helper function to get all subgenres for a specific genre
  function getAllSubgenresForGenre(genreName) {
    const genre = genres.find(g => g.name === genreName);
    return genre ? genre.subgenres.map(sub => sub.toLowerCase().replace(/\s+/g, '_')) : [];
  }
</script>
