<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">
      {{ __('Venue') }}
    </h1>
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative shadow-md sm:rounded-lg">
      <div class="min-w-screen-xl mx-auto max-w-screen-xl bg-opac_8_black px-16 py-12 text-white">
        <div class="header flex gap-4">
          @if ($venue->logo_url)
            <img src="{{ asset($venue->logo_url) }}" alt="{{ $venue->name }} Logo" class="_250img">
          @endif
          <div class="header-text flex flex-col justify-center gap-2">
            <h1 class="text-sans text-4xl">{{ $venue->name }}</h1>
            <p class="font-sans text-2xl">{{ $venue->postal_town }}</p>
            <div>
              <x-contact-and-social-links :item="$venue" />
            </div>
            <div class="rating-wrapper flex flex-row items-center gap-2">
              <p>Overall Rating ({{ $reviewCount }}): </p>
              <div class="ratings flex">
                {!! $overallReviews[$venue->id] !!}
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
                <a href="#" data-tab="in-house-gear"
                  class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:text-yns_yellow">
                  <span class="fas fa-cogs mr-2"></span>In House Gear
                </a>
              </li>
              <li class="tab me-2">
                <a href="#" data-tab="band-types-genres"
                  class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:text-yns_yellow">
                  <svg class="me-2 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path
                      d="M5 11.424V1a1 1 0 1 0-2 0v10.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.228 3.228 0 0 0 0-6.152ZM19.25 14.5A3.243 3.243 0 0 0 17 11.424V1a1 1 0 0 0-2 0v10.424a3.227 3.227 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.243 3.243 0 0 0 2.25-3.076Zm-6-9A3.243 3.243 0 0 0 11 2.424V1a1 1 0 0 0-2 0v1.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0V8.576A3.243 3.243 0 0 0 13.25 5.5Z" />
                  </svg>Band Types & Genres
                </a>
              </li>
              <li class="tab me-2">
                <a href="#" data-tab="reviews"
                  class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:text-yns_yellow">
                  <span class="fas fa-star mr-2"></span> Reviews
                </a>
              </li>
              <li class="tab me-2">
                <a href="#" data-tab="other"
                  class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:text-yns_yellow">
                  <span class="fas fa-plus mr-2"></span> Other
                </a>
              </li>
            </ul>
          </div>

          <div class="venue-tab-content mt-4 overflow-auto font-sans text-lg text-white">
            <div id="about">
              @if (!$venue->description)
                <p>We're still working on this! Come back later to read about us!</p>
              @else
                <p>{{ $venue->description }}</p>
              @endif
            </div>

            <div id="in-house-gear" class="max-h-80 flex h-full flex-col gap-4 overflow-auto">
              @if (!$venue->in_house_gear || $venue->in_house_gear == 'None')
                <p>We do not have any avaliable in house gear to use so you will be required to bring your own. Please
                  <a class="underline hover:text-yns_yellow" href="mailto:{{ $venue->contact_email }}">contact
                    us.</a> if you have any questions about what you can bring.
                </p>
              @else
                <p>We have the following gear in house. If you require the use of anything imparticular please <a
                    class="underline hover:text-yns_yellow" href="mailto:{{ $venue->contact_email }}">contact
                    us.</a>
                </p>
                <div class="gear-block flex flex-col">
                  <p class="text-base text-white">
                    {!! $venue->in_house_gear !!}
                  </p>
                </div>
              @endif
            </div>

            <div id="band-types-genres">
              @php
                $bandTypes = json_decode($venue->band_type);
              @endphp
              @if (!$bandTypes)
                <p>We don't have any specific band types listed, please <a class="underline hover:text-yns_yellow"
                    href="mailto:{{ $venue->contact_email }}">contact us.</a> if you would like to enquire about booking
                  your band.</p>
              @else
                <p class="mb-2">The band types that we usually have at <span
                    class="bold">{{ $venue->name }}</span>
                  are:</p>
                <ul class="band-types-list">
                  @foreach ($bandTypes as $type)
                    @switch($type)
                      @case('original-bands')
                        <li class="ml-6">Original Bands</li>
                      @break

                      @case('cover-bands')
                        <li class="ml-6">Cover Bands</li>
                      @break

                      @case('tribute-bands')
                        <li class="ml-6">Tribute Bands</li>
                      @break

                      @case('all')
                        <li class="ml-6">All Band Types</li>
                      @break

                      @default
                    @endswitch
                  @endforeach
                </ul>
                <p class="mt-2">If you would like to enquire about a show, please <a
                    class="underline hover:text-yns_yellow" href="mailto:{{ $venue->email }}">contact us.</a></p>
              @endif

              <p class="mt-4">The genres that we usually have at {{ $venue->name }} are:</p>

              @php
                $genres = json_decode($venue->genre);
              @endphp

              <!-- Split into 3 columns using Tailwind -->
              <ul class="genre-list columns-3 gap-4">
                @foreach ($genres as $genre)
                  <li class="ml-6">{{ $genre }}</li>
                @endforeach
              </ul>

              <p class="mt-4">If you would like to enquire about a show, please <a
                  class="underline hover:text-yns_yellow" href="mailto:{{ $venue->contact_email }}">contact us.</a></p>
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

              @if ($venue->recentReviews)
                <div class="reviews-block mt-8 flex flex-col gap-4">
                  @foreach ($venue->recentReviews as $review)
                    <div class="review text-center font-sans">
                      <p class="flex flex-col">"{{ $review->review }}" <span>- {{ $review->author }}</span></p>
                    </div>
                  @endforeach
                </div>
              @endif
            </div>

            <div id="other">
              @if ($venue->capacity)
                <p class="bold pb-2 text-2xl">Other Information you may want to know about {{ $venue->name }}.</p>
                @if ($venue->contact_name)
                  <p>Person(s) To Speak To: {{ $venue->contact_name }}</p>
                @endif
                @if ($venue->capacity)
                  <p class="pb-2">Capacity: {{ $venue->capacity }}</p>
                @endif
                <p class="bold pb-2 pt-2 text-2xl">More Info:</p>
                <p class="pb-2">{!! nl2br(e($venue->additional_info)) !!}</p>
            </div>
          @else
            <p>No Further Information Avaliable</p>
            @endif
          </div>
        </div>
        <x-suggestion-block :existingPromoters="$existingPromoters" :promoterWithHighestRating="$promoterWithHighestRating" :photographerWithHighestRating="$photographerWithHighestRating" :videographerWithHighestRating="$videographerWithHighestRating" :bandWithHighestRating="$bandWithHighestRating"
          :designerWithHighestRating="$designerWithHighestRating" />
        <x-review-modal title="{{ $venue->name }}" route="submit-venue-review" profileId="{{ $venue->id }}" />
      </div>
    </div>
  </div>
</x-guest-layout>
