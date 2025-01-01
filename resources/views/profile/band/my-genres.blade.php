<header>
  <h2 class="text-md font-heading font-medium text-white">
    {{ __('Your Genres') }}
  </h2>
</header>

<form action="{{ route('band.update', ['dashboardType' => $dashboardType, 'user' => $user->id]) }}" method="POST"
  class="mt-4">
  @csrf
  @method('PUT')
  <div class="mt-4 grid sm:grid-cols-2 sm:gap-3 lg:grid-cols-3 lg:gap-4">
    @php
      $bandGenres = is_string($band->genre) ? explode(',', $band->genre) : $band->genre;
    @endphp

    <!-- "All Genres" checkbox -->
    <div class="flex items-center">
      <input id="all-genres" name="all-genres" type="checkbox" value=""
        class="genre-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
        {{ in_array('All', $bandGenres) ? 'checked' : '' }}>
      <label for="all-genres" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">All Genres</label>
    </div>

    <!-- Genres -->
    @foreach ($genres as $index => $genre)
      <div>
        <div class="accordion" id="accordion-container-{{ $index }}">
          <div class="accordion-item">
            <input type="checkbox"
              class="genre-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
              id="all-genre-{{ $index }}" name="genres[]" value="{{ $genre['name'] }}"
              {{ in_array($genre['name'], $bandGenres) ? 'checked' : '' }}>
            <label for="all-genre-{{ $index }}"
              class="accordion-title ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
              All {{ $genre['name'] }}
            </label>

            <div class="accordion-content mt-2 pl-4">
              @foreach ($genre['subgenres'] as $subIndex => $subgenre)
                <div class="checkbox-wrapper mb-2 flex items-center">
                  <input type="checkbox"
                    class="subgenre-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
                    id="genre-{{ $index }}-subgenre-{{ $subIndex }}" name="genres[]"
                    value="{{ $subgenre }}" {{ in_array($subgenre, $bandGenres) ? 'checked' : '' }}>
                  <label class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100"
                    for="subgenre-{{ $index }}-{{ $subIndex }}">{{ $subgenre }}</label>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="mt-4">
    <button type="submit" class="rounded bg-blue-500 px-4 py-2 text-white">Update Genres</button>
  </div>
</form>

<script>
  const dashboardType = "{{ $dashboardType }}";
  const userId = "{{ $user->id }}";

  let selectedGenres = "{{ json_encode($bandGenres) }}";

  jQuery('.genre-checkbox, .subgenre-checkbox').each(function() {
    jQuery(this).prop('checked', selectedGenres.includes(jQuery(this).val()));
  });

  // Initialize variables
  let debounceTimer;
  let activeCheckbox = false; // Flag to check if user is actively selecting checkboxes

  // Collect selected genres and subgenres after a pause in user activity
  function collectAndSendData() {
    if (activeCheckbox) return; // Skip if user is still clicking checkboxes

    let selectedGenres = [];
    let selectedSubgenres = [];

    // Gather selected genres
    jQuery('.genre-checkbox:checked').each(function() {
      selectedGenres.push(jQuery(this).val());
    });

    // Gather selected subgenres
    jQuery('.subgenre-checkbox:checked').each(function() {
      selectedSubgenres.push(jQuery(this).val());
    });

    let mergedGenres = selectedGenres.concat(selectedSubgenres);

    console.log(mergedGenres);
  }

  // Debounce function
  function debouncedSave() {
    clearTimeout(debounceTimer);

    // Set a slight delay to ensure no more selections are made
    debounceTimer = setTimeout(() => {
      activeCheckbox = false; // Reset active flag after user stops interacting
      collectAndSendData();
    }, 500); // Adjust debounce time as needed
  }

  // Handle All Genres checkbox
  jQuery('#all-genres').change(function() {
    activeCheckbox = true; // User is actively selecting
    var isChecked = jQuery(this).prop('checked');
    jQuery(".genre-checkbox, .subgenre-checkbox").prop("checked", isChecked);
  });

  // Handle individual genre checkboxes
  jQuery('.genre-checkbox').change(function() {
    activeCheckbox = true; // User is actively selecting
    var isChecked = jQuery(this).prop('checked');
    var genreIndex = jQuery(this).attr('id').split('-')[2];

    jQuery('input[type="checkbox"][id*="genre-' + genreIndex + '-subgenre"]').prop('checked', isChecked);
    updateAllGenresCheckbox();
  });

  // Handle individual subgenre checkboxes
  jQuery('.subgenre-checkbox').change(function() {
    activeCheckbox = true; // User is actively selecting
    jQuery('#all-genres').prop('checked', false); // Uncheck "All Genres"
    updateAllGenresCheckbox();
  });

  // Update "All Genres" checkbox based on other selections
  function updateAllGenresCheckbox() {
    const allGenresChecked = jQuery(".genre-checkbox").length === jQuery(".genre-checkbox:checked").length;
    jQuery('#all-genres').prop('checked', allGenresChecked);
  }
</script>
