<header>
  <h2 class="text-md font-heading font-medium text-white">
    {{ __('Your Bands') }}
  </h2>
</header>

<form action="{{ route('profile.update', ['dashboardType' => $dashboardType, 'user' => $user->id]) }}" method="POST">
  @csrf
  @method('PUT')
  <div class="mt-4 grid sm:grid-cols-2 sm:gap-3 lg:grid-cols-3 lg:gap-4">
    @php
      $promoterGenres = is_array($promoterGenres) ? $promoterGenres : explode(',', $promoterGenres);
    @endphp

    <!-- "All Genres" checkbox -->
    <div class="flex items-center">
      <input id="all-genres" name="all-genres" type="checkbox" value=""
        class="genre-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
        onchange="toggleAllGenres(this)" {{ in_array('All', $promoterGenres) ? 'checked' : '' }}>
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
              {{ in_array($genre['name'], $promoterGenres) ? 'checked' : '' }}>
            <label for="all-genre-{{ $index }}"
              class="accordion-title ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
              All {{ $genre['name'] }}
            </label>

            @error('genres[]')
              <span class="text-red-500">{{ $message }}</span>
            @enderror

            <div class="accordion-content mt-2 pl-4">
              @foreach ($genre['subgenres'] as $subIndex => $subgenre)
                <div class="checkbox-wrapper mb-2 flex items-center">
                  <input type="checkbox"
                    class="subgenre-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
                    id="genre-{{ $index }}-subgenre-{{ $subIndex }}" name="genres[]"
                    value="{{ $subgenre }}" {{ in_array($subgenre, $promoterGenres) ? 'checked' : '' }}
                    data-parent-genre="{{ $genre['name'] }}">
                  <label class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100"
                    for="subgenre-{{ $index }}-{{ $subIndex }}">{{ $subgenre }}</label>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    @endforeach

    <div class="mt-4">
      <x-button type="submit" label="Save"></x-button>
    </div>
  </div>
</form>

<script>
  // Event handler for "All Genres" checkbox
  function toggleAllGenres(checkbox) {
    var isChecked = checkbox.checked;
    $(".genre-checkbox").prop("checked", isChecked);

    // If "All Genres" is checked, select all subgenres of each genre
    if (isChecked) {
      $(".accordion-item .subgenre-checkbox").prop("checked", true); // Check subgenres
    } else {
      $(".accordion-item .subgenre-checkbox").prop("checked", false); // Uncheck subgenres
    }

    updateAllGenresCheckbox();
    applyFilters();
  }

  // Event handler for individual genre checkboxes
  $('.genre-checkbox').change(function() {
    var isChecked = $(this).prop('checked');
    var genreId = $(this).attr('id');

    var genreIdParts = genreId.split('-');
    var genreIndex = genreIdParts[2];

    var subgenreCheckboxes = $('input[type="checkbox"][id*="genre-' + genreIndex + '-subgenre"]');
    subgenreCheckboxes.prop('checked', isChecked);

    updateAllGenresCheckbox();
    applyFilters();
  });

  // Event handler for subgenre checkboxes
  $('.subgenre-checkbox').change(function() {
    // If a subgenre checkbox is selected, deselect the "All Genres" checkbox
    $('#all-genres').prop('checked', false);
    updateAllGenresCheckbox();
    applyFilters();
  });

  function updateAllGenresCheckbox() {
    var allGenresChecked = true;
    $(".genre-checkbox").each(function() {
      if (!$(this).prop('checked')) {
        allGenresChecked = false;
      }
    });
    $('#all-genres').prop('checked', allGenresChecked);
  }

  function applyFilters() {
    // Get selected genre and subgenre values
    var selectedGenres = [];
    $('.genre-checkbox:checked').each(function() {
      selectedGenres.push($(this).val());
    });

    var selectedSubgenres = [];
    $('.subgenre-checkbox:checked').each(function() {
      selectedSubgenres.push($(this).val());
    });

    var mergedGenres = selectedGenres.concat(selectedSubgenres);

    // Example of how you can use the merged genres for filtering
    console.log("Selected Genres and Subgenres:", mergedGenres);
  }
</script>
