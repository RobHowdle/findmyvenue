<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">
      {{ __('Other') }}
    </h1>
  </x-slot>
  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative shadow-md sm:rounded-lg">
      <div class="min-w-screen-xl mx-auto max-w-screen-xl bg-opac_8_black px-16 py-12 text-white">
        <div class="header flex gap-4">
          @if ($singleService->logo_url)
            <img src="{{ asset($singleService->logo_url) }}" alt="{{ $singleService->name }} Logo" class="_250img">
          @endif
          <div class="header-text flex flex-col justify-center gap-2">
            <h1 class="text-sans text-4xl">{{ $singleService->name }}</h1>
            <p class="font-sans text-2xl">{{ $singleService->postal_town }}</p>
            <div>
              <x-contact-and-social-links :item="$singleService" />
            </div>
            <div class="rating-wrapper flex flex-row items-center gap-2">
              <p>Overall Rating (
                @if ($singleService->services == 'Band')
                  {{ $bandReviewCount }}
                  )
                  @else{{ $reviewCount }}
                @endif:
              </p>
              <div class="ratings flex">
                {!! $overallReviews[$singleService->id] !!}
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
          @if ($singleService->services == 'Band')
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
                  <a href="#" data-tab="members"
                    class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:text-yns_yellow">
                    <span class="fas fa-cog mr-2"></span>Members
                  </a>
                </li>
                <li class="tab me-2">
                  <a href="#" data-tab="music"
                    class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:text-yns_yellow">
                    <span class="fas fa-cog mr-2"></span>Music
                  </a>
                </li>
                <li class="tab me-2">
                  <a href="#" data-tab="reviews"
                    class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:text-yns_yellow">
                    <span class="fas fa-star mr-2"></span> Reviews
                  </a>
                </li>
                <li class="tab me-2">
                  <a href="#" data-tab="socials"
                    class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:text-yns_yellow">
                    <span class="fas fa-plus mr-2"></span> Socials
                  </a>
                </li>
              </ul>
            </div>
          @else
            <div class="h-auto py-4">
              @php
                $spotifyUrl = 'https://open.spotify.com/track/4PTG3Z6ehGkBFwjybzWkR8?si=23c6845e25df4307';
              @endphp
              <ul
                class="flex flex-wrap justify-between border-b border-gray-200 text-center text-sm font-medium text-gray-500 dark:border-gray-700 dark:text-gray-400">
                <li class="tab me-2 pl-0">
                  <a href="#" data-tab="overview"
                    class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:text-yns_yellow">
                    <span class="fas fa-info-circle mr-2"></span>Overview
                  </a>
                </li>
                <li class="tab me-2">
                  <a href="#" data-tab="services"
                    class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:text-yns_yellow">
                    <span class="fas fa-cog mr-2"></span>Services
                  </a>
                </li>
                <li class="tab me-2">
                  <a href="#" data-tab="reviews"
                    class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:text-yns_yellow">
                    <span class="fas fa-star mr-2"></span> Reviews
                  </a>
                </li>
                <li class="tab me-2">
                  <a href="#" data-tab="socials"
                    class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:text-yns_yellow">
                    <span class="fas fa-plus mr-2"></span> Socials
                  </a>
                </li>
              </ul>
            </div>
          @endif

          @if ($singleService->services == 'Band')
            <div class="venue-tab-content mt-4 overflow-auto font-sans text-lg text-white">
              <div id="about">
                @if (!$singleService->description)
                  <p>We're still working on this! Come back later to read about us!</p>
                @else
                  <p class="underline">About</p>
                  <p class="mb-4">{{ $singleService->description }}</p>

                  <p class="underline"> Genre & Type</p>
                  <p>We are {{ a0rAn($genre->genres[0]) }}
                    @if (is_array($genre->genres))
                      @foreach ($genre->genres as $index => $type)
                        {{ $type }}@if (!$loop->last)
                          ,
                        @endif
                      @endforeach
                    @else
                      Unknown type
                    @endif
                    that play
                    @if (is_array($bandType->band_type))
                      @foreach ($bandType->band_type as $index => $type)
                        {{ $type }}@if (!$loop->last)
                          ,
                        @endif
                      @endforeach
                    @else
                      Unknown type
                    @endif
                    music.
                  </p>

                @endif
              </div>

              <div id="members" class="flex flex-wrap gap-4 overflow-auto">
                @if ($members)
                  <div class="service min-w-[calc(50%-1rem)] flex-1">
                    <ul class="list-inside list-none">
                      @foreach ($members as $member)
                        <li>{{ $member->name }} - {{ $member->role }}</li>
                      @endforeach
                    </ul>
                  </div>
                @else
                  <p>We haven't got our services set up yet! Come back soon!</p>
                @endif
              </div>

              <div id="music">
                <p class="mb-4 text-center text-2xl font-bold">Listen To Us</p>
                <p class="mb-4 text-center">Our music is avaliable on the following platforms. Feel free to give us a
                  follow
                  to stay updated with our releases!</p>
                @php
                  $streamUrls = $streamUrls ?? [];
                  $spotifyUrl = '';
                  $otherLinks = [];

                  foreach ($streamUrls as $link) {
                      if (stripos($link->host, 'Spotify') !== false) {
                          $spotifyUrl = $link->url;
                      } else {
                          $otherLinks[] = $link;
                      }
                  }

                  if ($spotifyUrl == null) {
                      $spotifyUrl = 'https://open.spotify.com/track/4PTG3Z6ehGkBFwjybzWkR8?si=23c6845e25df4307';
                  }

                @endphp
                @if ($spotifyUrl)
                  <div id="embed-iframe" loading="lazy" allowfullscreen="" frameBorder="0"
                    allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"></div>
                @endif

                <p class="my-4 text-center text-2xl font-bold">Also Catch Us On</p>
                @php
                  $linkCount = count($otherLinks);
                @endphp
                <div
                  class="streaming-platforms grid-cols-{{ $linkCount }} grid place-items-center items-center gap-4">
                  @foreach ($otherLinks as $link)
                    <a href="{{ $link->url }}" target="_blank"
                      class="streaming-platforms transition hover:bg-yns_yellow" rel="noopener noreferrer">
                      <img src="{{ asset('storage/images/system/streaming/' . strtolower($link->host) . '.png') }}"
                        alt="{{ $link->host }} Streaming Link" class="streaming-platform-logo">
                    </a>
                  @endforeach
                </div>
              </div>

              <div id="reviews">
                <p class="text-center">Want to know what we're like? Check out our reviews!</p>
                <div class="ratings-block mt-4 flex flex-col items-center gap-4">
                  <p class="grid grid-cols-2">Communication:
                    <span class="rating-wrapper flex flex-row gap-3">
                      {!! $renderRatingIcons($bandAverageCommunicationRating) !!}
                    </span>
                  </p>
                  <p class="grid grid-cols-2">Music:
                    <span class="rating-wrapper flex flex-row gap-3">
                      {!! $renderRatingIcons($bandAverageMusicRating) !!}
                    </span>
                  </p>
                  <p class="grid grid-cols-2">Promotion:
                    <span class="rating-wrapper flex flex-row gap-3">
                      {!! $renderRatingIcons($bandAveragePromotionRating) !!}
                    </span>
                  </p>
                  <p class="grid grid-cols-2">Gig Quality:
                    <span class="rating-wrapper flex flex-row gap-3">
                      {!! $renderRatingIcons($bandAverageGigQualityRating) !!}
                    </span>
                  </p>
                </div>

                @if ($singleService->recentReviews)
                  <div class="reviews-block mt-8 flex flex-col gap-4">
                    @foreach ($singleService->recentReviews as $review)
                      <div class="review text-center font-sans">
                        <p class="flex flex-col">"{{ $review->review }}" <span>- {{ $review->author }}</span></p>
                      </div>
                    @endforeach
                  </div>
                @endif
              </div>

              <div id="socials">
                @if ($singleService->platforms)
                  @foreach ($singleService->platforms as $platform)
                    @if ($platform['platform'] == 'facebook')
                      <a class="mb-4 mr-2 flex items-center hover:text-yns_yellow" href="{{ $platform['url'] }}"
                        target="_blank">
                        <span class="fab fa-facebook mr-4 h-10"></span> {{ $platform['url'] }}
                      </a>
                    @elseif($platform['platform'] == 'twitter')
                      <a class="mb-4 mr-2 flex items-center hover:text-yns_yellow" href="{{ $platform['url'] }}"
                        target="_blank">
                        <span class="fab fa-twitter mr-4 h-10"></span> {{ $platform['url'] }}
                      </a>
                    @elseif($platform['platform'] == 'instagram')
                      <a class="mb-4 mr-2 flex items-center hover:text-yns_yellow" href="{{ $platform['url'] }}"
                        target="_blank">
                        <span class="fab fa-instagram mr-4 h-10"></span> {{ $platform['url'] }}
                      </a>
                    @elseif($platform['platform'] == 'snapchat')
                      <a class="mb-4 mr-2 flex items-center hover:text-yns_yellow" href="{{ $platform['url'] }}"
                        target="_blank">
                        <span class="fab fa-snapchat-ghost mr-4 h-10"></span> {{ $platform['url'] }}
                      </a>
                    @elseif($platform['platform'] == 'tiktok')
                      <a class="mb-4 mr-2 flex items-center hover:text-yns_yellow" href="{{ $platform['url'] }}"
                        target="_blank">
                        <span class="fab fa-tiktok mr-4 h-10"></span> {{ $platform['url'] }}
                      </a>
                    @elseif($platform['platform'] == 'youtube')
                      <a class="mb-4 mr-2 flex items-center hover:text-yns_yellow" href="{{ $platform['url'] }}"
                        target="_blank">
                        <span class="fab fa-youtube mr-4 h-10"></span> {{ $platform['url'] }}
                      </a>
                    @endif
                  @endforeach
                @else
                  <p>No socials here yet! Check back later!</p>
                @endif
              </div>
            </div>
          @else
            <div class="venue-tab-content mt-4 overflow-auto font-sans text-lg text-white">
              <div id="overview">
                @if (!$singleService->description)
                  <p>We're still working on this! Come back later to read about us!</p>
                @else
                  <p>{{ $singleService->description }}</p>
                @endif
              </div>

              <div id="services" class="flex flex-wrap gap-4 overflow-auto">
                @if ($services)
                  @foreach ($services as $service)
                    <div class="service min-w-[calc(50%-1rem)] flex-1">
                      <p class="font-semibold">{{ $service->packageTitle }}</p>

                      @if (is_array($service->packageDescription))
                        <ul class="list-inside list-disc">
                          @foreach ($service->packageDescription as $bullet)
                            <li>{{ $bullet }}</li>
                          @endforeach
                        </ul>
                      @endif

                      <p class="mt-4 text-lg font-bold">From {{ $service->packageCost }}</p>
                    </div>
                  @endforeach
                  <p class="mt-4">All services are subject to location and travel costs. Please <a
                      class="underline hover:text-yns_yellow"
                      href="mailto:{{ $singleService->contact_email }}">contact
                      us</a> with any
                    queries.</p>
                  @if ($singleService->portfolio_link)
                    <p class="mt-2">You can view our portfolio by <a class="underline hover:text-yns_yellow"
                        href="{{ $singleService->portfolio_link }}" target="_blank">clicking here.</a></p>
                  @endif
                @else
                  <p>We haven't got our services set up yet! Come back soon!</p>
                @endif
              </div>

              <div id="reviews">
                <p class="text-center">Want to know what we're like? Check out our reviews!</p>
                <div class="ratings-block mt-4 flex flex-col items-center gap-4">
                  <p class="grid grid-cols-2">Communication:
                    <span class="rating-wrapper flex flex-row gap-3">
                      {!! $renderRatingIcons($averageCommunicationRating) !!}
                    </span>
                  </p>
                  <p class="grid grid-cols-2">Price:
                    <span class="rating-wrapper flex flex-row gap-3">
                      {!! $renderRatingIcons($averageRopRating) !!}
                    </span>
                  </p>
                  <p class="grid grid-cols-2">Promotion:
                    <span class="rating-wrapper flex flex-row gap-3">
                      {!! $renderRatingIcons($averagePromotionRating) !!}
                    </span>
                  </p>
                  <p class="grid grid-cols-2">Quality:
                    <span class="rating-wrapper flex flex-row gap-3">
                      {!! $renderRatingIcons($averageQualityRating) !!}
                    </span>
                  </p>
                </div>

                @if ($singleService->recentReviews)
                  <div class="reviews-block mt-8 flex flex-col gap-4">
                    @foreach ($singleService->recentReviews as $review)
                      <div class="review text-center font-sans">
                        <p class="flex flex-col">"{{ $review->review }}" <span>- {{ $review->author }}</span></p>
                      </div>
                    @endforeach
                  </div>
                @endif
              </div>

              <div id="socials">
                @if ($singleService->platforms)
                  @foreach ($singleService->platforms as $platform)
                    @if ($platform['platform'] == 'facebook')
                      <a class="mb-4 mr-2 flex items-center hover:text-yns_yellow" href="{{ $platform['url'] }}"
                        target="_blank">
                        <span class="fab fa-facebook mr-4 h-10"></span> {{ $platform['url'] }}
                      </a>
                    @elseif($platform['platform'] == 'twitter')
                      <a class="mb-4 mr-2 flex items-center hover:text-yns_yellow" href="{{ $platform['url'] }}"
                        target="_blank">
                        <span class="fab fa-twitter mr-4 h-10"></span> {{ $platform['url'] }}
                      </a>
                    @elseif($platform['platform'] == 'instagram')
                      <a class="mb-4 mr-2 flex items-center hover:text-yns_yellow" href="{{ $platform['url'] }}"
                        target="_blank">
                        <span class="fab fa-instagram mr-4 h-10"></span> {{ $platform['url'] }}
                      </a>
                    @elseif($platform['platform'] == 'snapchat')
                      <a class="mb-4 mr-2 flex items-center hover:text-yns_yellow" href="{{ $platform['url'] }}"
                        target="_blank">
                        <span class="fab fa-snapchat-ghost mr-4 h-10"></span> {{ $platform['url'] }}
                      </a>
                    @elseif($platform['platform'] == 'tiktok')
                      <a class="mb-4 mr-2 flex items-center hover:text-yns_yellow" href="{{ $platform['url'] }}"
                        target="_blank">
                        <span class="fab fa-tiktok mr-4 h-10"></span> {{ $platform['url'] }}
                      </a>
                    @elseif($platform['platform'] == 'youtube')
                      <a class="mb-4 mr-2 flex items-center hover:text-yns_yellow" href="{{ $platform['url'] }}"
                        target="_blank">
                        <span class="fab fa-youtube mr-4 h-10"></span> {{ $platform['url'] }}
                      </a>
                    @endif
                  @endforeach
                @else
                  <p>No socials here yet! Check back later!</p>
                @endif
              </div>
            </div>
          @endif
          <x-suggestion-block :promoterWithHighestRating="$promoterWithHighestRating" :photographerWithHighestRating="$photographerWithHighestRating" :videographerWithHighestRating="$videographerWithHighestRating" :bandWithHighestRating="$bandWithHighestRating"
            :designerWithHighestRating="$designerWithHighestRating" />
          <x-review-modal title="{{ $singleService->name }}" route="submit-venue-review"
            profileId="{{ $singleService->id }}" />
        </div>
      </div>
    </div>
</x-guest-layout>
<script src="https://open.spotify.com/embed/iframe-api/v1" async></script>
<script>
  console.log('running');
  window.onSpotifyIframeApiReady = (IFrameAPI) => {
    const element = document.getElementById('embed-iframe');
    const options = {
      uri: '{{ $spotifyUrl }}'
    };
    const callback = (EmbedController) => {};
    IFrameAPI.createController(element, options, callback);
  };
</script>
