@props(['venues', 'genres'])

<div class="py-12">
  <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
    <h1 class="text-center font-heading text-6xl text-white">Venues</h1>
    <div class="relative mt-4 overflow-x-auto shadow-md sm:rounded-lg">
      <div class="search-wrapper flex justify-center">
        <form class="filter-search p-4" action="{{ route('venues') }}" method="GET">
          <div class="search-bar flex justify-end">
            <input class="search map-input flex w-2/6 justify-center font-sans text-xl" type="search" id="address-input"
              name="search_query" placeholder="Search..." value="{{ $searchQuery ?? '' }}" />
            <button type="submit" class="search-button bg-white p-2 text-black">
              <span class="fas fa-search"></span>
            </button>
          </div>
          <div class="filters relative">
            <div id="accordion-collapse" data-accordion="collapse">
              <h2 id="accordion-collapse-heading-1">
                <button type="button"
                  class="flex w-full items-center justify-between gap-3 rounded-t-xl p-5 font-medium text-white"
                  data-accordion-target="#accordion-collapse-body-1" aria-expanded="true"
                  aria-controls="accordion-collapse-body-1">
                  <span>Filters <span class="fas fa-filter"></span></span>
                  <svg data-accordion-icon class="h-3 w-3 shrink-0 rotate-180" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5 5 1 1 5" />
                  </svg>
                </button>
              </h2>
              <div id="accordion-collapse-body-1" class="absolute hidden"
                aria-labelledby="accordion-collapse-heading-1">
                <div class="border border-b-0 border-gray-200 p-5 dark:border-gray-700 dark:bg-gray-900">
                  <div class="group relative z-0 mb-5 w-full">
                    <label class="text-sm font-medium text-gray-900 dark:text-gray-300">Preferred Band Types</label>
                    <div class="mt-4 grid grid-cols-3 gap-4">
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
                    <div class="mt-4 grid grid-cols-3 gap-4">
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
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <table class="w-full border-2 border-white text-left font-sans rtl:text-right" id="venues">
        <!-- Table headers -->
        <thead class="text-2xl text-white underline">
          <tr>
            <th scope="col" class="px-6 py-3">Venue</th>
            <th scope="col" class="px-6 py-3">Location</th>
            <th scope="col" class="px-6 py-3">Contact</th>
            <th scope="col" class="px-6 py-3">Promoter</th>
          </tr>
        </thead>
        <tbody>
          <!-- Table rows -->
          {{ $slot }}
        </tbody>
      </table>
      <!-- Pagination links -->
      <div class="mt-4 px-6 py-3">
        @if ($venues->isNotEmpty())
          {{ $venues->links() }}
        @endif
      </div>
    </div>
  </div>
</div>
