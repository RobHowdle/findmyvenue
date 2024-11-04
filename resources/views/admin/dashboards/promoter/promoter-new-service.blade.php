<x-app-layout :dashboardType="$dashboardType" :modules="$modules">
  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg bg-yns_dark_gray px-16 py-12 text-white">
        <p class="mb-3 text-3xl font-bold text-white">Oops, you're not linked to anywhere! Let's fix that!</p>
        <div class="mb-4 grid grid-cols-2 gap-x-8 gap-y-4">
          <div class="group">
            <x-input-label-dark>What is the name of your Promotions Company?
              <span id="result-count"></span>
            </x-input-label-dark>
            <x-text-input id="promoter-search"></x-text-input>
            <ul class="mt-2 flex flex-col gap-4 rounded-lg" id="promoter-results"></ul>
          </div>
        </div>

        <div class="mb-4">
          <div class="col-span-2" id="create-promoter-form" style="display: none;">
            <p class="col-span-2 mb-3 font-bold">It looks like you're not already in the system - Let's get you added!
            </p>
            <form action="{{ route('admin.dashboard.promoter.store') }}" class="grid grid-cols-2 gap-x-8 gap-y-4"
              id="promoter-create-form" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="group">
                <x-input-label-dark>Where are you based?</x-input-label-dark>
                <x-text-input id="address-input" name="address-input" class="map-input"></x-text-input>
                @error('address-input')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
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
                @error('postal-town-input')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
                <input
                  class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
                  type="text" id="address-latitude" name="latitude" placeholder="Latitude"
                  value="{{ old('latitude') }}">
                @error('latitude')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
                <input
                  class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
                  type="text" id="address-longitude" name="longitude" placeholder="Longitude"
                  value="{{ old('longitude') }}">
                @error('longitude')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
              </div>

              <div class="group">
                <x-input-label-dark>Promotions Company Name</x-input-label-dark>
                <x-text-input id="name" name="name" value="{{ old('name') }}"></x-text-input>
                @error('name')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
              </div>

              <div class="group">
                <x-input-label-dark>Logo</x-input-label-dark>
                <x-input-file id="promoter_logo" name="promoter_logo"></x-input-file>
                @error('promoter_logo')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
              </div>

              <div class="group">
                <x-input-label-dark>Tell us a bit about you</x-input-label-dark>
                <x-textarea-input class="w-full" id="description"
                  name="description">{{ old('description') }}</x-textarea-input>
                @error('description')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
              </div>

              <div class="col-span-2">
                <div class="group">
                  <x-input-label-dark>What venues have you used? <span class="text-sm">Separate by
                      comma</span></x-input-label-dark>
                  <x-text-input id="my_venues" name="my_venues" value="{{ old('my_venues') }}"></x-text-input>
                  @error('my_venues')
                    <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              <div class="group relative z-0 mb-5 w-full">
                <x-input-label-dark>Preferred Band Types</x-input-label-dark>
                <div class="sm-gap:3 mt-4 grid sm:grid-cols-2 lg:grid-cols-3 lg:gap-4">
                  <div class="flex items-center gap-2">
                    <x-input-checkbox id="all-bands" name="band_type[]" value="all" class="band-checkbox" />
                    <x-input-label-dark class="mb-0" for="all-bands">All Types</x-input-label-dark>
                  </div>
                  <div class="flex items-center gap-2">
                    <x-input-checkbox id="original-bands" name="band_type[]" value="original-bands"
                      class="band-checkbox" />
                    <x-input-label-dark class="mb-0" for="original-bands">Original</x-input-label-dark>
                  </div>
                  <div class="flex items-center gap-2">
                    <x-input-checkbox id="cover-bands" name="band_type[]" value="cover-bands" class="band-checkbox" />
                    <x-input-label-dark class="mb-0" for="cover-bands">Covers</x-input-label-dark>
                  </div>
                  <div class="flex items-center gap-2">
                    <x-input-checkbox id="tribute-bands" name="band_type[]" value="tribute-bands"
                      class="band-checkbox" />
                    <x-input-label-dark class="mb-0" for="tribute-bands">Tributes</x-input-label-dark>
                  </div>
                  @error('band_type')
                    <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              <div class="group relative z-0 mb-5 w-full">
                <x-input-label-dark>Preferred Genre(s) - <span>Yes, there is a lot</span></x-input-label-dark>
                <div class="mt-4 grid sm:grid-cols-2 sm:gap-3 lg:grid-cols-3 lg:gap-4">
                  <!-- "All Genres" checkbox -->
                  <div>
                    <div class="flex items-center gap-2">
                      <x-input-checkbox id="all-genres" name="all-genres" value=""
                        class="all-genres-checkbox" />
                      <x-input-label-dark class="mb-0" for="all-genres">All Genres</x-input-label-dark>
                    </div>
                  </div>
                  <!-- Genres -->
                  @foreach ($genres as $index => $genre)
                    <div>
                      <div class="accordion" id="accordion-container">
                        <div class="accordion-item">
                          <x-input-checkbox id="all-genre-{{ $index }}" name="genres[]"
                            value="All {{ $genre['name'] }}" class="genre-checkbox"
                            data-parent-genre="{{ $index }}" />
                          <x-input-label-dark class="accordion-title mb-0 inline"
                            for="all-genre-{{ $index }}">All {{ $genre['name'] }}</x-input-label-dark>

                          <div class="accordion-content">
                            @foreach ($genre['subgenres'] as $subIndex => $subgenre)
                              <div class="checkbox-wrapper">
                                <x-input-checkbox id="genre-{{ $index }}-subgenre-{{ $subIndex }}"
                                  name="genres[]" value="{{ $subgenre }}" class="subgenre-checkbox"
                                  data-parent-genre="{{ $index }}" />
                                <x-input-label-dark class="inline"
                                  for="genre-{{ $index }}-subgenre-{{ $subIndex }}">{{ $subgenre }}</x-input-label-dark>
                              </div>
                            @endforeach
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>

              <div class="group">
                <x-input-label-dark for="contact_number">Contact Number</x-input-label-dark>
                <x-text-input id="contact_number" name="contact_number" />
              </div>
              <div class="group">
                <x-input-label-dark for="contact_email">Contact Email</x-input-label-dark>
                <x-text-input id="contact_email" name="contact_email" />
              </div>
              <div class="group">
                <x-input-label-dark for="contact_link">Social Links</x-input-label-dark>
                <x-text-input id="contact_link" name="contact_link" />
              </div>

              <div class="group relative z-0 mb-5 w-full" x-data="{ isMainContact: 'true' }">
                <x-input-label-dark>Are you the main contact for the promoter?</x-input-label-dark>

                <!-- Radio buttons for Yes/No -->
                <div class="my-4 flex items-center gap-4">
                  <div class="flex items-center gap-2">
                    <x-input-radio id="main-contact-yes" name="is_main_contact" value="true"
                      x-model="isMainContact"></x-input-radio>
                    <x-input-label-dark for="main-contact-yes" class="mb-0">Yes</x-input-label-dark>
                  </div>
                  <div class="flex items-center gap-2">
                    <x-input-radio id="main-contact-no" name="is_main_contact" value="false"
                      x-model="isMainContact"></x-input-radio>
                    <x-input-label-dark for="main-contact-no" class="mb-0">No</x-input-label-dark>
                  </div>
                </div>

                <!-- Fields for main contact information -->
                <div x-show="isMainContact === 'false'" x-cloak class="mt-4">
                  <div class="mb-4">
                    <x-input-label-dark for="contact_name">Contact Name</x-input-label-dark>
                    <x-text-input id="contact_name" name="contact_name" />
                  </div>
                </div>

                <div class="group">
                  <button type="submit"
                    class="mt-8 flex w-full justify-center rounded-lg border border-yns_cyan bg-yns_cyan px-4 py-2 font-heading text-xl text-black transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">Save</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>

<script>
  $(document).ready(function() {
    const genres = @json($genres);
    const allBandsCheckbox = $('#all-bands');
    const bandCheckboxes = $('.band-checkbox').not('#all-bands');

    allBandsCheckbox.change(function() {
      const isChecked = $(this).prop('checked');

      bandCheckboxes.prop('checked', isChecked);
    });

    bandCheckboxes.change(function() {
      const allChecked = bandCheckboxes.length === bandCheckboxes.filter(':checked').length;

      allBandsCheckbox.prop('checked', allChecked);
    });

    function populateGenres(genres) {
      const genresContainer = $('#genres-container');
      genresContainer.empty(); // Clear existing genres

      genres.forEach((genre, genreIndex) => {
        // Create the main genre accordion item
        const accordionItem = $('<div class="accordion-item"></div>');

        // Create the header for the genre with a checkbox for the entire genre
        const header = $(`
            <h2 class="accordion-header">
                <div class="flex items-center gap-2">
                    <x-input-checkbox id="all-genre-${genreIndex}" name="genres[]" value="All ${genre.name}" class="genre-checkbox" data-all-genre-id="${genreIndex}" />
                    <x-input-label-dark class="accordion-title mb-0 inline" for="all-genre-${genreIndex}">All ${genre.name}</x-input-label-dark>
                </div>
            </h2>
        `);

        // Create the body for subgenres
        const body = $('<div class="accordion-body" style="display: none;"></div>');

        // Populate subgenres
        genre.subgenres.forEach((subgenre, subIndex) => {
          const subgenreCheckbox = $(`
                <div class="checkbox-wrapper">
                    <x-input-checkbox id="subgenre-${genreIndex}-${subIndex}" name="genres[]" value="${subgenre}" class="subgenre-checkbox" data-parent-genre="${genreIndex}" />
                    <x-input-label-dark class="inline" for="subgenre-${genreIndex}-${subIndex}">${subgenre}</x-input-label-dark>
                </div>
            `);
          body.append(subgenreCheckbox);
        });

        // Append header and body to the accordion item
        accordionItem.append(header).append(body);
        genresContainer.append(accordionItem);
      });

      // Attach event listeners after populating the genres
      attachGenreEventListeners();
    }

    function attachGenreEventListeners() {
      // Accordion toggle functionality for genres
      $(document).on('click', '.accordion-header', function() {
        $(this).next('.accordion-body').slideToggle();
      });

      // Event listener for the "All Genres" checkbox
      $(document).on('change', '.all-genres-checkbox', function() {
        const isChecked = $(this).prop('checked');

        // Check/uncheck all individual genre and subgenre checkboxes based on "All Genres"
        $('.genre-checkbox').prop('checked', isChecked);
        $('.subgenre-checkbox').prop('checked', isChecked);
      });

      // Event listener for individual genre checkboxes
      $(document).on('change', '.genre-checkbox', function() {
        const genreId = $(this).attr('id').split('-')[2]; // Extract the genre index
        const isChecked = $(this).prop('checked');

        // Check/uncheck all subgenres of this genre based on the "All Genre" checkbox
        $(`.subgenre-checkbox[data-parent-genre="${genreId}"]`).prop('checked', isChecked);

        // Check if any subgenre is unchecked
        const allSubgenresChecked = $(`.subgenre-checkbox[data-parent-genre="${genreId}"]:checked`)
          .length ===
          $(`.subgenre-checkbox[data-parent-genre="${genreId}"]`).length;

        // Update the "All Genre" checkbox based on subgenre states
        $(this).prop('checked', allSubgenresChecked);
      });

      // Event listener for subgenre checkboxes
      $(document).on('change', '.subgenre-checkbox', function() {
        const genreId = $(this).data('parent-genre');

        // Check if all subgenres of the parent genre are checked
        const allSubgenresChecked = $(`.subgenre-checkbox[data-parent-genre="${genreId}"]:checked`).length ===
          $(`.subgenre-checkbox[data-parent-genre="${genreId}"]`).length;

        // Update the "All Genre" checkbox based on subgenre states
        $(`#all-genre-${genreId}`).prop('checked', allSubgenresChecked);
      });

      // Event listener for accordion toggle to ensure states are checked correctly
      $(document).on('click', '.accordion-header', function() {
        const genreId = $(this).find('.genre-checkbox').attr('id').split('-')[2];
        const allSubgenresChecked = $(`.subgenre-checkbox[data-parent-genre="${genreId}"]:checked`).length ===
          $(`.subgenre-checkbox[data-parent-genre="${genreId}"]`).length;

        // Only check the "All Genre" checkbox if all subgenres are checked when the accordion is opened
        if ($(this).next('.accordion-body').is(':visible') && allSubgenresChecked) {
          $(`#all-genre-${genreId}`).prop('checked', true);
        } else {
          $(`#all-genre-${genreId}`).prop('checked', false);
        }
      });
    }

    // Promoter search functionality
    $('#promoter-search').on('keyup', function() {
      let query = $(this).val();

      $.ajax({
        url: '{{ route('admin.dashboard.promoter.search') }}',
        type: 'GET',
        data: {
          query: query
        },
        success: function(data) {
          $('#promoter-results').html('');
          $('#result-count').text('');

          if (data.count > 0) {
            data.results.forEach(function(promoter) {
              $('#promoter-results').append(
                '<li class="flex px-2 flex-row items-center justify-between"><p>' +
                promoter.name +
                '</p><button class="bg-white text-black rounded-lg px-4 py-2 font-heading transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow" data-id="' +
                promoter.id +
                '">Link</button></li>');
            });
            $('#create-promoter-form').fadeOut(500);
            $('#result-count').text(data.count + ' results found');
          } else {
            $('#promoter-results').html('<li>No promoter found</li>');
            $('#create-promoter-form').fadeIn(500);
            $('#result-count').text('0 results');
          }
        }
      });
    });

    // Event delegation for dynamically created buttons
    $('#promoter-results').on('click', 'button', function() {
      const promoterId = $(this).data('id'); // Retrieve the id from data-id attribute
      linkUserToPromoter(promoterId); // Call your function
    });

    function linkUserToPromoter(promoterId) {
      $.ajax({
        url: '{{ route('admin.dashboard.promoter.link') }}',
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          serviceable_id: promoterId
        },
        success: function(response) {
          showSuccessNotification(response.message);
          setTimeout(function() {
            window.location.href = response.redirect_url;
          }, 3000);
        }
      });
    }


    populateGenres(genres);
  });
</script>
