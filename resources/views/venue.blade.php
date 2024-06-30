<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">
      {{ __('Venues') }}
    </h1>
  </x-slot>

  <div class="venue-wrapper min-w-screen-xl relative mx-auto my-6 w-full max-w-screen-xl p-8">
    <div class="header flex gap-4">
      @if ($venue->logo_url)
        <img src="{{ asset($venue->logo_url) }}" alt="{{ $venue->name }} Logo" class="venue-logo">
      @endif
      <div class="header-text flex flex-col justify-end gap-2">
        <h1 class="text-sans text-4xl text-white">{{ $venue->name }}</h1>
        <p class="font-sans text-2xl text-white">{{ $venue->postal_town }}</p>
        <div class="socials-wrapper flex flex-row gap-4">
          @if ($venue->contact_number || $venue->contact_email || $venue->contact_link ?? 'N/A')
            @if ($venue->contact_number)
              <a class="hover:text-white" href="tel:{{ $venue->contact_number }}"><span class="fas fa-phone"></span></a>
            @endif
            @if ($venue->contact_email)
              <a class="hover:text-white" href="mailto:{{ $venue->contact_email }}"><span
                  class="fas fa-envelope"></span></a>
            @endif
            @if ($venue->platforms)
              @foreach ($venue->platforms as $platform)
                @if ($platform['platform'] == 'facebook')
                  <a class="hover:text-white" href="{{ $platform['url'] }}" target=_blank><span
                      class="fab fa-facebook"></span></a>
                @elseif($platform['platform'] == 'twitter')
                  <a class="hover:text-white" href="{{ $platform['url'] }}" target=_blank><span
                      class="fab fa-twitter"></span></a>
                @elseif($platform['platform'] == 'instagram')
                  <a class="hover:text-white" href="{{ $platform['url'] }}" target=_blank><span
                      class="fab fa-instagram"></span></a>
                @endif
              @endforeach
            @endif
          @endif
        </div>
        <div class="rating-wrapper flex flex-row items-center gap-2">
          <p>Overall Rating ({{ $overallReview }}): </p>
          @php
            $fullIcons = floor($overallReview); // Number of full icons
            $fraction = $overallReview - $fullIcons; // Fractional part of the rating
          @endphp
          @for ($i = 0; $i < $fullIcons; $i++)
            <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
          @endfor
          @if ($fraction > 0)
            <div class="partially-filled-icon"
              style="width: {{ ($overallReview - floor($overallReview)) * 32.16 }}px; overflow: hidden;">
              <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
            </div>
          @endif
        </div>
        <div class="leave-review">
          <button class="px-6 py-2 text-sm text-gray-900 hover:bg-gray-600 hover:text-gray-300 dark:bg-gray-400">Visited
            us? <span data-modal-target="review-modal" data-modal-toggle="review-modal" type="button">Leave Us A
              Review</span></button>
        </div>
      </div>
    </div>
    <div class="body">
      <div class="h-auto py-4">
        <ul
          class="flex flex-wrap border-b border-gray-200 text-center text-sm font-medium text-gray-500 dark:border-gray-700 dark:text-gray-400">
          <li class="tab me-2 pl-0">
            <a href="#" data-tab="about"
              class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:border-gray-300 hover:text-gray-600 dark:hover:text-gray-300">
              <span class="fas fa-info-circle mr-2"></span>About
            </a>
          </li>
          <li class="tab me-2">
            <a href="#" data-tab="in-house-gear"
              class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:border-gray-300 hover:text-gray-600 dark:hover:text-gray-300">
              <span class="fas fa-cogs mr-2"></span>In House Gear
            </a>
          </li>
          <li class="tab me-2">
            <a href="#" data-tab="band-types"
              class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:border-gray-300 hover:text-gray-600 dark:hover:text-gray-300">
              <svg class="me-2 h-4 w-4 text-white group-hover:text-white dark:text-white dark:group-hover:text-white"
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path
                  d="M5 11.424V1a1 1 0 1 0-2 0v10.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.228 3.228 0 0 0 0-6.152ZM19.25 14.5A3.243 3.243 0 0 0 17 11.424V1a1 1 0 0 0-2 0v10.424a3.227 3.227 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.243 3.243 0 0 0 2.25-3.076Zm-6-9A3.243 3.243 0 0 0 11 2.424V1a1 1 0 0 0-2 0v1.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0V8.576A3.243 3.243 0 0 0 13.25 5.5Z" />
              </svg>Band Types
            </a>
          </li>
          <li class="tab me-2">
            <a href="#" data-tab="genres"
              class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:border-gray-300 hover:text-gray-600 dark:hover:text-gray-300">
              <svg class="me-2 h-4 w-4 text-white group-hover:text-white dark:text-white dark:group-hover:text-white"
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                <path
                  d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z" />
              </svg>Genres
            </a>
          </li>
          <li class="tab me-2">
            <a href="#" data-tab="reviews"
              class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:border-gray-300 hover:text-gray-600 dark:hover:text-gray-300">
              <span class="fas fa-star mr-2"></span> Reviews
            </a>
          </li>
          <li class="tab me-2">
            <a href="#" data-tab="other"
              class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:border-gray-300 hover:text-gray-600 dark:hover:text-gray-300">
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
          @if (!$venue->in_house_gear)
            <p>We do not have any avaliable in house gear to use so you will be required to bring your own. Please
              contact
              us if you have any questions about what you can bring.</p>
          @else
            <p>We have the following gear in house. If you require the use of anything imparticular please contact us.
            </p>
            <div class="gear-block flex flex-col">
              <p class="text-base text-white">
                {{ $venue->in_house_gear }}
              </p>
            </div>
          @endif
        </div>

        <div id="band-types">
          @php
            $bandTypes = json_decode($venue->band_type);
          @endphp
          @if (!$bandTypes)
            <p>We don't have any specific band types listed, please contact us if you would like to enquire about
              booking your band.</p>
          @else
            <p>The band types that we usually have at <span class="bold">{{ $venue->name }}</span> are:</p>
            <ul class="band-types-list mb-2">
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

                  @case('all-bands')
                    <li class="ml-6">All Band Types</li>
                  @break

                  @default
                @endswitch
              @endforeach
            </ul>
            <p>If you would like to enquire about a show, please contact us.</p>
          @endif
        </div>

        <div id="genres">
          <p>The genres that we usually have at {{ $venue->name }} are:</p>
          @php $genres = json_decode($venue->genre); @endphp
          <ul class="genre-list mb-2">
            @foreach ($genres as $genre)
              <li class="ml-6">{{ $genre }}</li>
            @endforeach
          </ul>
          <p>If you would like to enquire about a show, please contact us.</p>
        </div>

        <div id="reviews">
          <p class="text-center">Want to know what we're like? Check out our reviews!</p>
          <div class="ratings-block mt-4 flex flex-col items-center gap-4">
            <p class="grid grid-cols-2">Communication:
              <span class="flex flex-row gap-3">
                @php
                  $fullIcons = floor($averageCommunicationRating); // Number of full icons
                  $fraction = $averageCommunicationRating - $fullIcons; // Fractional part of the rating
                @endphp
                @for ($i = 0; $i < $fullIcons; $i++)
                  <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png"
                    alt="sign-of-the-horns-emoji" />
                @endfor
                @if ($fraction > 0)
                  <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji"
                    class="partially-filled-icon" style="width: {{ $fraction * 32.16 }}px; overflow: hidden;" />
                @endif
              </span>
            </p>
            <p class="grid grid-cols-2">Rate Of Pay:
              <span class="flex flex-row gap-3">
                @php
                  $fullIcons = floor($averageRopRating); // Number of full icons
                  $fraction = $averageRopRating - $fullIcons; // Fractional part of the rating
                @endphp
                @for ($i = 0; $i < $fullIcons; $i++)
                  <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png"
                    alt="sign-of-the-horns-emoji" />
                @endfor
                @if ($fraction > 0)
                  <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji"
                    class="partially-filled-icon" style="width: {{ $fraction * 32.16 }}px; overflow: hidden;" />
                @endif
              </span>
            </p>
            <p class="grid grid-cols-2">Promotion:
              <span class="flex flex-row gap-3">
                @php
                  $fullIcons = floor($averagePromotionRating); // Number of full icons
                  $fraction = $averagePromotionRating - $fullIcons; // Fractional part of the rating
                @endphp
                @for ($i = 0; $i < $fullIcons; $i++)
                  <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png"
                    alt="sign-of-the-horns-emoji" />
                @endfor
                @if ($fraction > 0)
                  <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji"
                    class="partially-filled-icon" style="width: {{ $fraction * 32.16 }}px; overflow: hidden;" />
                @endif
              </span>
            </p>
            <p class="grid grid-cols-2">Gig Quality:
              <span class="flex flex-row gap-3">
                @php
                  $fullIcons = floor($averageQualityRating); // Number of full icons
                  $fraction = $averageQualityRating - $fullIcons; // Fractional part of the rating
                @endphp
                @for ($i = 0; $i < $fullIcons; $i++)
                  <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png"
                    alt="sign-of-the-horns-emoji" />
                @endfor
                @if ($fraction > 0)
                  <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji"
                    class="partially-filled-icon" style="width: {{ $fraction * 32.16 }}px; overflow: hidden;" />
                @endif
              </span>
            </p>
          </div>

          <div class="reviews-block mt-8 flex flex-col gap-4">
            @foreach ($venue->recentReviews as $review)
              <div class="review text-center font-sans">
                <p class="flex flex-col">"{{ $review->review }}" <span>- {{ $review->author }}</span></p>
              </div>
            @endforeach
          </div>
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
  </div>

  <div
    class="suggestion-wrapper min-w-screen-xl relative mx-auto my-6 mt-8 w-full max-w-screen-xl border-2 border-white p-8 text-white">
    <p class="text-3xl text-white">Suggestions</p>
    @if ($existingPromoters->isNotEmpty())
      <p class="my-4">These promoters already work with this venue</p>
      @foreach ($existingPromoters as $promoter)
        <div class="suggestion-block">
          <a href="{{ route('promoter', $promoter->id) }}">
            @if ($promoter->logo_url)
              <img src="{{ asset($promoter->logo_url) }}" alt="{{ $promoter->name }} Logo" class="promoter-logo">
            @endif
            <p>{{ $promoter->name }}</p>
          </a>
        </div>
      @endforeach
    @else
      <p class="my-4">This venue doesn't have any in-house promoters, here are some suggestions!</p>
      <div class="suggestion-row grid grid-cols-3 gap-2">
        @foreach ($promotersByLocation as $promoter)
          <div class="suggestion-block col-span-1">
            <a href="{{ route('promoter', $promoter->id) }}">
              @if ($promoter->logo_url)
                <img src="{{ asset($promoter->logo_url) }}" alt="{{ $promoter->name }} Logo" class="promoter-logo">
              @endif
              <p>{{ $promoter->name }}</p>
            </a>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  {{-- Review Modal --}}
  <!-- Main modal -->
  <div id="review-modal" tabindex="-1" aria-hidden="true"
    class="max-h-full fixed left-0 right-0 top-0 z-50 flex hidden h-[calc(100%-1rem)] w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0">
    <div class="max-h-full relative m-4 mx-auto w-full max-w-4xl border border-white">
      <div class="review-popup relative rounded-lg bg-white dark:bg-black">
        <div class="dark:white flex items-center justify-between rounded-t border-b p-4 md:p-5">
          <h3 class="text-xl font-semibold">
            Leave a review for {{ $venue->name }}
          </h3>
          <button type="button"
            class="ms-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white"
            data-modal-hide="default-modal">
            <span class="fas fa-times"></span>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <!-- Modal body -->
        <div class="space-y-4 p-4 md:p-5">
          <form action="{{ route('submit-venue-review', $venue->id) }}" method="POST">
            @csrf
            <div class="rating-block grid grid-cols-2">
              <p>Communitcation:</p>
              <div class="rating">
                <input type="radio" name="communication-rating" value="1"
                  class="bg-[url(https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png)]" />
                <input type="radio" name="communication-rating" value="2"
                  class="bg-[url(https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png)]" />
                <input type="radio" name="communication-rating" value="3"
                  class="bg-[url(https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png)]" />
                <input type="radio" name="communication-rating" value="4"
                  class="bg-[url(https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png)]" />
                <input type="radio" name="communication-rating" value="5"
                  class="bg-[url(https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png)]" />
              </div>
            </div>
            <div class="rating-block grid grid-cols-2">
              <p>Rate Of Pay:</p>
              <div class="rating">
                <input type="radio" name="rop-rating" value="1"
                  class="bg-[url(https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png)]" />
                <input type="radio" name="rop-rating" value="2"
                  class="bg-[url(https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png)]" />
                <input type="radio" name="rop-rating" value="3"
                  class="bg-[url(https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png)]" />
                <input type="radio" name="rop-rating" value="4"
                  class="bg-[url(https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png)]" />
                <input type="radio" name="rop-rating" value="5"
                  class="bg-[url(https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png)]" />
              </div>
            </div>
            <div class="rating-block grid grid-cols-2">
              <p>Promotion:</p>
              <div class="rating">
                <input type="radio" name="promotion-rating" value="1"
                  class="bg-[url(https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png)]" />
                <input type="radio" name="promotion-rating" value="2"
                  class="bg-[url(https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png)]" />
                <input type="radio" name="promotion-rating" value="3"
                  class="bg-[url(https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png)]" />
                <input type="radio" name="promotion-rating" value="4"
                  class="bg-[url(https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png)]" />
                <input type="radio" name="promotion-rating" value="5"
                  class="bg-[url(https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png)]" />
              </div>
            </div>
            <div class="rating-block grid grid-cols-2">
              <p>Gig Quality:</p>
              <div class="rating">
                <input type="radio" name="quality-rating" value="1"
                  class="bg-[url(https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png)]" />
                <input type="radio" name="quality-rating" value="2"
                  class="bg-[url(https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png)]" />
                <input type="radio" name="quality-rating" value="3"
                  class="bg-[url(https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png)]" />
                <input type="radio" name="quality-rating" value="4"
                  class="bg-[url(https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png)]" />
                <input type="radio" name="quality-rating" value="5"
                  class="bg-[url(https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png)]" />
              </div>
            </div>

            <div class="review-block mt-4">
              <div class="group relative z-0 mb-5 hidden w-full">
                <input type="text" name="reviewer_ip" id="reviewer_ip" value="{{ old('reviewer_ip') }}"
                  class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
                  placeholder=" " required readonly />
                <label for="reviewer_ip"
                  class="absolute top-3 -z-10 origin-[0] -translate-y-6 scale-75 transform text-sm text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:start-0 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:font-medium peer-focus:text-blue-600 rtl:peer-focus:left-auto rtl:peer-focus:translate-x-1/4 dark:text-gray-400 peer-focus:dark:text-blue-500">
                  IP
                </label>
                @error('reviewer_ip')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="group relative z-0 mb-5 w-full">
                <input type="text" name="review_author" id="review_author" value="{{ old('review_author') }}"
                  class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
                  placeholder=" " required />
                <label for="review_author"
                  class="absolute top-3 -z-10 origin-[0] -translate-y-6 scale-75 transform text-sm text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:start-0 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:font-medium peer-focus:text-blue-600 rtl:peer-focus:left-auto rtl:peer-focus:translate-x-1/4 dark:text-gray-400 peer-focus:dark:text-blue-500">
                  Your Name<span class="required">*</span>
                </label>
                @error('review_author')
                  <span class="text-danger">{{ $message }}</span>
                @enderror
                <div class="group relative z-0 mb-5 w-full">
                  <textarea name="review_message" id="review_message" value="{{ old('review_message') }}"
                    class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
                    placeholder=" " required></textarea>
                  <label for="review_message"
                    class="absolute top-3 -z-10 origin-[0] -translate-y-6 scale-75 transform text-sm text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:start-0 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:font-medium peer-focus:text-blue-600 rtl:peer-focus:left-auto rtl:peer-focus:translate-x-1/4 dark:text-gray-400 peer-focus:dark:text-blue-500">
                    Your Review<span class="required">*</span>
                  </label>
                  @error('review_message')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
            </div>
            <button data-modal-hide="review-modal" type="submit"
              class="rounded-lg border border-white px-5 py-2.5 text-center font-sans text-sm font-medium text-white dark:hover:bg-blue-950">Submit
              Review</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-guest-layout>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  $(document).ready(function() {
    // Hide all tab contents except the first one
    $(".venue-tab-content > div:not(:first)").hide();

    // Add active class to the default tab link
    $(".tabLinks:first").addClass(
      "active text-blue-600 border-b-2 border-blue-600 rounded-t-lg dark:text-blue-500 dark:border-blue-500 group"
    );

    // Add click event to tab links
    $(".tabLinks").click(function() {
      // Get the tab ID from the data attribute
      var tabId = $(this).data("tab");

      // Hide all tab contents
      $(".venue-tab-content > div").hide();

      // Show the selected tab content
      $("#" + tabId).fadeIn();

      // Remove "active" class from all tab links
      $(".tabLinks").removeClass(
        "active text-blue-600 border-b-2 border-blue-600 rounded-t-lg dark:text-blue-500 dark:border-blue-500 group"
      );

      // Add "active" class to the clicked tab link
      $(this).addClass(
        "active text-blue-600 border-b-2 border-blue-600 rounded-t-lg dark:text-blue-500 dark:border-blue-500 group"
      );

      // Prevent default link behavior
      return false;
    });
  });

  document.addEventListener("DOMContentLoaded", function() {
    const modalButtons = document.querySelectorAll("[data-modal-toggle]");
    const modalCloseButtons = document.querySelectorAll("[data-modal-hide]");
    const modal = document.getElementById("review-modal");

    // Function to show the modal
    const showModal = () => {
      modal.classList.remove("hidden");
      modal.setAttribute("aria-hidden", "false");
    };

    // Function to hide the modal
    const hideModal = () => {
      modal.classList.add("hidden");
      modal.setAttribute("aria-hidden", "true");
    };

    // Add click event listeners to toggle modal visibility
    modalButtons.forEach((button) => {
      button.addEventListener("click", showModal);
    });

    modalCloseButtons.forEach((button) => {
      button.addEventListener("click", hideModal);
    });
  });

  var venueId = {{ json_encode($venueId) }};

  $.ajax({
    url: "{{ route('suggestPromoters') }}",
    method: "GET",
    data: {
      venue_id: venueId
    },
    success: function(response) {
      var existingPromoters = response.existingPromoters;
      var promotersByLocation = response.promotersByLocation;

      // Now you can update your UI, for example, by passing these variables to your component
      var promoterSuggestionsComponent = document.querySelector('x-promoter-suggestions');
      promoterSuggestionsComponent.setAttribute('venueId', venueId);
      promoterSuggestionsComponent.setAttribute('existingPromoters', JSON.stringify(existingPromoters));
      promoterSuggestionsComponent.setAttribute('promotersByLocation', JSON.stringify(promotersByLocation));

    },
    error: function(xhr, status, error) {
      // Handle errors
      console.error(xhr.responseText);
    }
  });
</script>

<script>
  $(document).ready(function() {
    $.getJSON('https://api.ipify.org?format=json', function(data) {
      var userIP = data.ip;
      // Verify the element exists before setting the value
      var reviewerIpField = $('#reviewer_ip');
      if (reviewerIpField.length) {
        reviewerIpField.val(userIP);
      }
    }).fail(function(jqxhr, textStatus, error) {
      var err = textStatus + ", " + error;
      console.error("Request Failed: " + err);
    });
  });
</script>
