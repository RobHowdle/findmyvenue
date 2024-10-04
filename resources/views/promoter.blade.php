<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">
      {{ __('Promoter') }}
    </h1>
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative shadow-md sm:rounded-lg">
      <div class="min-w-screen-xl mx-auto max-w-screen-xl bg-opac_8_black px-16 py-12 text-white">
        <div class="header flex gap-4">
          @if ($promoter->logo_url)
            <img src="{{ asset($promoter->logo_url) }}" alt="{{ $promoter->name }} Logo" class="_250img">
          @endif
          <div class="header-text flex flex-col justify-center gap-2">
            <h1 class="text-sans text-4xl">{{ $promoter->name }}</h1>
            <p class="font-sans text-2xl">{{ $promoter->postal_town }}</p>
            <div>
              <x-contact-and-social-links :item="$promoter" />
            </div>
            <div class="rating-wrapper flex flex-row items-center gap-2">
              <p>Overall Rating ({{ $reviewCount }}): </p>
              <div class="ratings flex">
                {!! $overallReviews[$promoter->id] !!}
              </div>
            </div>
            <div class="leave-review">
              <button
                class="from-yns_dark_orange rounded bg-gradient-to-t to-yns_yellow px-6 py-2 text-sm text-black hover:bg-yns_yellow"
                data-modal-toggle="review-modal" type="button">Leave a review</button>
            </div>
          </div>
        </div>

        <div class="body">
          <div class="h-auto py-4">
            <ul
              class="flex flex-wrap justify-between border-b border-gray-200 text-center text-sm font-medium text-gray-500 dark:border-gray-700 dark:text-gray-400">
              <li class="tab me-2 pl-0">
                <a href="#" data-tab="about"
                  class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:text-yns_yellow">
                  <span class="fas fa-info-circle mr-2"></span>About
                </a>
              </li>
              <li class="tab me-2">
                <a href="#" data-tab="my-venues"
                  class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:text-yns_yellow">
                  <span class="fas fa-cogs mr-2"></span>My Venues
                </a>
              </li>
              <li class="tab me-2">
                <a href="#" data-tab="bands-and-genres"
                  class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:text-yns_yellow">
                  <svg class="me-2 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path
                      d="M5 11.424V1a1 1 0 1 0-2 0v10.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.228 3.228 0 0 0 0-6.152ZM19.25 14.5A3.243 3.243 0 0 0 17 11.424V1a1 1 0 0 0-2 0v10.424a3.227 3.227 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.243 3.243 0 0 0 2.25-3.076Zm-6-9A3.243 3.243 0 0 0 11 2.424V1a1 1 0 0 0-2 0v1.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0V8.576A3.243 3.243 0 0 0 13.25 5.5Z" />
                  </svg>Bands & Genres
                </a>
              </li>
              <li class="tab me-2">
                <a href="#" data-tab="reviews"
                  class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:text-yns_yellow">
                  <span class="fas fa-star mr-2"></span> Reviews
                </a>
              </li>
            </ul>
          </div>

          <div class="venue-tab-content mt-4 overflow-auto font-sans text-lg text-white">
            <div id="about">
              @if (!$promoter->description)
                <p>We're still working on this! Come back later to read about us!</p>
              @else
                <p>{{ $promoter->description }}</p>
              @endif
            </div>

            <div id="my-venues" class="max-h-80 flex h-full flex-col gap-4 overflow-auto">
              @if (!$promoter->my_venues)
                <p>We're still working on this! Come back later to read about us!</p>
              @else
                <p>{{ $promoter->my_venues }}</p>
              @endif
            </div>

            <div id="bands-and-genres">
              @php
                $bandTypes = json_decode($promoter->band_type);
              @endphp
              @if (!$bandTypes)
                <p class="mb-2">We don't have any specific band types that we prefer to work with, please <a
                    class="underline hover:text-yns_yellow" href="mailto:{{ $promoter->contact_email }}">contact us.</a>
                  if you would like to enquire about promoting your band.</p>
              @else
                <p class="mb-2"><span class="bold">{{ $promoter->name }}</span> specifically promotes
                  @foreach ($bandTypes as $type)
                    @switch($type)
                      @case('original-bands')
                        Original Bands
                      @break

                      @case('cover-bands')
                        Cover Bands
                      @break

                      @case('tribute-bands')
                        Tribute Bands
                      @break

                      @case('all')
                        All Band Types
                      @break

                      @default
                    @endswitch
                  @endforeach
              @endif
              </p>
              @php
                $genres = json_decode($promoter->genre);
              @endphp
              <p class="mb-2">
                We like to work mostly with artists in the
                @foreach ($genres as $index => $genre)
                  {{ $genre }}@if ($index < count($genres) - 1)
                    ,
                  @endif
                @endforeach
                genres.
              </p>
            </div>

            <div id="reviews">
              <p class="text-center">Want to know what we're like? Check out our reviews!</p>
              <div class="ratings-block mt-4 flex flex-col items-center gap-4">
                <p class="grid grid-cols-2">Communication:
                  <span class="rating-wrapper flex flex-row gap-3">
                    {!! $renderRatingIcons($averageCommunicationRating) !!}
                  </span>
                </p>
                <p class="grid grid-cols-2">Rate Of Pay:
                  <span class="rating-wrapper flex flex-row gap-3">
                    {!! $renderRatingIcons($averageRopRating) !!}
                  </span>
                </p>
                <p class="grid grid-cols-2">Promotion:
                  <span class="rating-wrapper flex flex-row gap-3">
                    {!! $renderRatingIcons($averagePromotionRating) !!}
                  </span>
                </p>
                <p class="grid grid-cols-2">Gig Quality:
                  <span class="rating-wrapper flex flex-row gap-3">
                    {!! $renderRatingIcons($averageQualityRating) !!}
                  </span>
                </p>
              </div>

              @if ($promoter->recentReviews)
                <div class="reviews-block mt-8 flex flex-col gap-4">
                  @foreach ($promoter->recentReviews as $review)
                    <div class="review text-center font-sans">
                      <p class="flex flex-col">"{{ $review->review }}" <span>- {{ $review->author }}</span></p>
                    </div>
                  @endforeach
                </div>
              @endif
            </div>
          </div>
        </div>
        <x-suggestion-block :existingVenues="$existingVenues" :venueWithHighestRating="$venueWithHighestRating" :photographerWithHighestRating="$photographerWithHighestRating" :videographerWithHighestRating="$videographerWithHighestRating" :bandWithHighestRating="$bandWithHighestRating"
          :designerWithHighestRating="$designerWithHighestRating" />

        <x-review-modal title="{{ $promoter->name }}" route="submit-venue-review" profileId="{{ $promoter->id }}" />
      </div>
    </div>
  </div>
</x-guest-layout>
