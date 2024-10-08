<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">
      {{ $singleServices }}
    </h1>
  </x-slot>

  <div class="mx-auto min-h-screen w-full max-w-screen-2xl">
    <h1 class="py-8 text-center font-heading text-6xl text-white">{{ $serviceName }}
    </h1>
    <div class="relative shadow-md sm:rounded-lg">
      <div class="search-wrapper flex justify-center border border-white dark:bg-black">
        <form class="filter-search flex items-center sm:p-1 md:p-3" action="{{ route('other.filterCheckboxesSearch') }}"
          method="GET">
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
              <div id="accordion-collapse-body-1" class="absolute hidden"
                aria-labelledby="accordion-collapse-heading-1">
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
            <input class="search flex w-full justify-center bg-opacBlack font-sans text-xl text-white" type="search"
              id="address-input" name="search_query" placeholder="Search..." value="{{ $searchQuery ?? '' }}" />
          </div>
        </form>
      </div>
      <div class="relative z-0 overflow-x-auto">
        <table class="w-full border border-white text-left font-sans rtl:text-right" id="otherServices">
          <thead class="text-white underline dark:bg-black">
            <tr>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Name
              </th>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Rating
              </th>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">
                Location
              </th>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">
                Contact
              </th>
            </tr>
          </thead>
          <tbody>
            @forelse ($singleServices as $service)
              <tr class="odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-black even:dark:bg-gray-900">
                <th scope="row"
                  class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
                  <a href="{{ route('singleService', ['serviceName' => $service->services, 'serviceId' => $service->id]) }}"
                    class="hover:text-ynsYellow">{{ $service->name }}</a>
                </th>
                <td class="rating-wrapper flex whitespace-nowrap sm:py-3 sm:text-base md:py-2 lg:py-4">
                  {!! $overallReviews[$service->id] !!}
                </td>
                <td
                  class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
                  {{ $service->postal_town }}
                </td>

                <td
                  class="flex gap-4 whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
                  @if ($service->contact_number)
                    <a class="hover:text-ynsYellow" href="tel:{{ $service->contact_number }}"><span
                        class="fas fa-phone"></span></a>
                  @endif
                  @if ($service->contact_email)
                    <a class="hover:text-ynsYellow" href="mailto:{{ $service->contact_email }}"><span
                        class="fas fa-envelope"></span></a>
                  @endif
                  @if ($service->platforms)
                    @foreach ($service->platforms as $platform)
                      @if ($platform['platform'] == 'facebook')
                        <a class="mr-2 hover:text-ynsYellow" href="{{ $platform['url'] }}" target=_blank><span
                            class="fab fa-facebook"></span></a>
                      @elseif($platform['platform'] == 'twitter')
                        <a class="mr-2 hover:text-ynsYellow" href="{{ $platform['url'] }}" target=_blank><span
                            class="fab fa-twitter"></span></a>
                      @elseif($platform['platform'] == 'instagram')
                        <a class="mr-2 hover:text-ynsYellow" href="{{ $platform['url'] }}" target=_blank><span
                            class="fab fa-instagram"></span></a>
                      @elseif($platform['platform'] == 'snapchat')
                        <a class="mr-2 hover:text-ynsYellow" href="{{ $platform['url'] }}" target=_blank><span
                            class="fab fa-snapchat-ghost"></span></a>
                      @elseif($platform['platform'] == 'tiktok')
                        <a class="mr-2 hover:text-ynsYellow" href="{{ $platform['url'] }}" target=_blank><span
                            class="fab fa-tiktok"></span></a>
                      @elseif($platform['platform'] == 'youtube')
                        <a class="mr-2 hover:text-ynsYellow" href="{{ $platform['url'] }}" target=_blank><span
                            class="fab fa-youtube"></span></a>
                      @endif
                    @endforeach
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="py-4 text-center">No services found</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</x-guest-layout>
