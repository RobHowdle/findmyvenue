@props(['singleServices', 'genres', 'serviceName'])

<div class="mx-auto min-h-screen w-full max-w-screen-2xl px-2 pt-24 md:pt-32">
  <h1 class="py-4 text-center font-heading text-3xl text-white md:py-6 md:text-4xl xl:text-5xl 4xl:text-6xl">
    {{ $serviceName }}</h1>
  <div class="relative shadow-md sm:rounded-lg">
    <div class="search-wrapper flex justify-center border border-white dark:bg-black">
      <div class="filter-search flex items-center sm:p-1 md:p-3">
        <div class="filters relative flex items-center">
          <div id="accordion-collapse" class="w-full" data-accordion="collapse">
            <h2 id="accordion-collapse-heading-1">
              <button type="button"
                class="filter-button flex h-full w-full items-center justify-between gap-3 rounded-t-xl text-xl font-medium text-white sm:p-1 md:p-3 lg:p-5"
                data-accordion-target="#accordion-collapse-body-1" aria-expanded="false"
                aria-controls="accordion-collapse-body-1">
                <span>Filters <span class="fas fa-filter"></span></span>
                <svg data-accordion-icon class="icon h-3 w-3 shrink-0 rotate-180" aria-hidden="true"
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
                                    id="genre-{{ $index }}-subgenre-{{ $subIndex }}" name="genres[]"
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
        <div class="search-bar flex items-center justify-end rounded border border-white">
          <input class="search flex w-full justify-center bg-opac_black font-sans text-xl text-white" type="search"
            id="address-input" name="search_query" placeholder="Search..." value="{{ $searchQuery ?? '' }}" />
        </div>
      </div>
    </div>
    <div class="relative z-0 overflow-x-auto">
      <table class="w-full border border-white text-left font-sans rtl:text-right" id="otherServices">
        <thead class="text-white underline dark:bg-black">
          <tr>
            <th scope="col" class="px-2 py-2 text-base md:px-6 md:py-3 md:text-xl lg:px-8 lg:py-4 lg:text-2xl">
              Name
            </th>
            <th scope="col"
              class="hidden px-2 py-2 text-base md:px-6 md:py-3 md:text-xl lg:block lg:px-8 lg:py-4 lg:text-2xl">
              Rating
            </th>
            <th scope="col" class="px-2 py-2 text-base md:px-6 md:py-3 md:text-xl lg:px-8 lg:py-4 lg:text-2xl">
              Location
            </th>
            <th scope="col"
              class="hidden px-2 py-2 text-base md:block md:px-6 md:py-3 md:text-xl lg:px-8 lg:py-4 lg:text-2xl">
              Contact
            </th>
          </tr>
        </thead>
        <tbody>
          {{ $slot }}
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="mt-4 bg-yns_dark_gray px-4 py-4">
  {{ $singleServices->links() }}
</div>
