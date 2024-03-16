@props(['venues', 'genres'])

<div class="w-full max-w-screen-xl">
  <h1 class="mt-6 text-center font-heading text-6xl text-white">Venues</h1>
  <div class="relative shadow-md sm:rounded-lg">
    <div class="search-wrapper flex justify-center border-2 border-white">
      <form class="filter-search flex items-center sm:p-1 md:p-3" action="{{ route('venues.filterCheckboxesSearch') }}"
        method="GET">
        <div class="filters relative flex items-center">
          <div id="accordion-collapse" class="w-full" data-accordion="collapse">
            <h2 id="accordion-collapse-heading-1">
              <button type="button"
                class="filter-button flex h-full w-full items-center justify-between gap-3 rounded-t-xl text-xl font-medium text-white sm:p-1 md:p-3 lg:p-5"
                data-accordion-target="#accordion-collapse-body-1" aria-expanded="false"
                aria-controls="accordion-collapse-body-1">
                <span>Filters <span class="fas fa-filter"></span></span>
                <svg data-accordion-icon class="rotate h-3 w-3 shrink-0 rotate-180" aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5 5 1 1 5" />
                </svg>
              </button>
            </h2>
            <div id="accordion-collapse-body-1" class="absolute hidden" aria-labelledby="accordion-collapse-heading-1">
              <div
                class="filter-content max-h-40rem overflow-y-auto border border-b-0 border-gray-200 p-5 dark:border-gray-700 dark:bg-gray-900">
                <div class="group relative z-0 mb-5 w-full">
                  <label class="text-sm font-medium text-gray-900 dark:text-gray-300">Preferred Band Types</label>
                  <div class="sm-gap:3 mt-4 grid sm:grid-cols-2 lg:grid-cols-3 lg:gap-4">
                    <div class="flex items-center">
                      <input id="all-bands" name="band_type[]" type="checkbox" value="all"
                        class="filter-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800" />
                      <label for="all-bands" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">All
                        Types</label>
                    </div>
                    <div class="flex items-center">
                      <input id="original-bands" name="original-bands" type="checkbox" value="original-bands"
                        class="filter-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800" />
                      <label for="original-bands"
                        class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Original</label>
                    </div>
                    <div class="flex items-center">
                      <input id="cover-bands" name="cover-bands" type="checkbox" value="cover-bands"
                        class="filter-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800" />
                      <label for="cover-bands"
                        class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Covers</label>
                    </div>
                    <div class="flex items-center">
                      <input id="tribute-bands" name="tribute-bands" type="checkbox" value="tribute-bands"
                        class="filter-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800" />
                      <label for="tribute-bands"
                        class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tributes</label>
                    </div>
                    @error('floating_band_type')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="group relative z-0 mb-5 w-full">
                  <label class="text-sm font-medium text-gray-900 dark:text-gray-300">Preferred Genre(s) -
                    <span>Yes,
                      there
                      is a lot</span></label>
                  <div class="mt-4 grid sm:grid-cols-2 sm:gap-3 lg:grid-cols-3 lg:gap-4">
                    <!-- "All Genres" checkbox -->
                    <div>
                      <div class="flex items-center">
                        <input id="all-genres" name="all-genres" type="checkbox" value=""
                          class="genre-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800" />
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
                                    {{ in_array($subgenre, old('genres', [])) ? 'checked' : '' }}
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
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="search-bar flex items-center justify-end rounded border-2 border-white">
          <input class="search flex w-full justify-center bg-opacBlack font-sans text-xl text-white" type="search"
            id="address-input" name="search_query" placeholder="Search..." value="{{ $searchQuery ?? '' }}" />
        </div>
      </form>
    </div>
    <div class="relative z-0 overflow-x-auto">
      <table class="w-full border-b-2 border-l-2 border-r-2 border-white text-left font-sans rtl:text-right"
        id="venues">
        <!-- Table headers -->
        <thead class="text-white underline">
          <tr>
            <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Venue
            </th>
            <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Location
            </th>
            <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Contact
            </th>
            <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Promoter
            </th>
          </tr>
        </thead>
        <tbody>
          <!-- Table rows -->
          {{ $slot }}
        </tbody>
      </table>
    </div>
    <!-- Pagination links -->
    <div class="mt-4 px-6 py-3">
      @if ($venues->isNotEmpty())
        {{ $venues->links() }}
      @endif
    </div>
  </div>
</div>
