<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">
      {{ __('Promoter') }}
    </h1>
  </x-slot>

  <div class="promoter-wrapper min-w-screen-xl relative mx-auto my-6 w-full max-w-screen-xl p-8">
    <div class="header flex gap-4">
      @if ($promoter->logo_url)
        <img src="{{ asset($promoter->logo_url) }}" alt="{{ $promoter->name }} Logo" class="promoter-logo">
      @endif
      <div class="header-text flex flex-col justify-end gap-2">
        <h1 class="text-sans text-4xl text-white">{{ $promoter->name }}</h1>
        <p class="font-sans text-2xl text-white">{{ $promoter->postal_town }}</p>
        <div class="socials-wrapper flex flex-row gap-4">
          @if ($promoter->contact_number || $promoter->contact_email || $promoter->contact_link ?? 'N/A')
            @if ($promoter->contact_number)
              <a class="hover:text-white" href="tel:{{ $promoter->contact_number }}"><span
                  class="fas fa-phone"></span></a>
            @endif
            @if ($promoter->contact_email)
              <a class="hover:text-white" href="mailto:{{ $promoter->contact_email }}"><span
                  class="fas fa-envelope"></span></a>
            @endif
            @if ($promoter->platforms)
              @foreach ($promoter->platforms as $platform)
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
          <button class="px-6 py-2 text-sm text-gray-900 hover:bg-gray-600 hover:text-gray-300 dark:bg-gray-400">Worked
            with us? <span data-modal-target="review-modal" data-modal-toggle="review-modal" type="button">Leave Me A
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
            <a href="#" data-tab="my-venues"
              class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:border-gray-300 hover:text-gray-600 dark:hover:text-gray-300">
              <span class="fas fa-cogs mr-2"></span>My Venues
            </a>
          </li>
          <li class="tab me-2">
            <a href="#" data-tab="bands"
              class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:border-gray-300 hover:text-gray-600 dark:hover:text-gray-300">
              <svg class="me-2 h-4 w-4 text-white group-hover:text-white dark:text-white dark:group-hover:text-white"
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path
                  d="M5 11.424V1a1 1 0 1 0-2 0v10.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.228 3.228 0 0 0 0-6.152ZM19.25 14.5A3.243 3.243 0 0 0 17 11.424V1a1 1 0 0 0-2 0v10.424a3.227 3.227 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.243 3.243 0 0 0 2.25-3.076Zm-6-9A3.243 3.243 0 0 0 11 2.424V1a1 1 0 0 0-2 0v1.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0V8.576A3.243 3.243 0 0 0 13.25 5.5Z" />
              </svg>Bands
            </a>
          </li>
          <li class="tab me-2">
            <a href="#" data-tab="reviews"
              class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:border-gray-300 hover:text-gray-600 dark:hover:text-gray-300">
              <span class="fas fa-star mr-2"></span> Reviews
            </a>
          </li>
        </ul>
      </div>

      <div class="promoter-tab-content mt-4 overflow-auto font-sans text-lg text-white">
        <div id="about">
          @if (!$promoter->decription)
            <p>We're still working on this! Come back later to read about us!</p>
          @else
            <p>{{ $promoter->description }}</p>
          @endif
        </div>

        <div id="my-venues" class="max-h-80 flex h-full flex-col gap-4 overflow-auto">
          @if (!$promoter->my_venues)
            <p>Still working on this part!</p>
          @else
            <div class="gear-block flex flex-col">
              <p class="text-base text-white">You can find at the following venues:</p>
              <ul class="band-types-list mb-2">
                @foreach ($promoter->venues as $venue)
                  <li><a target="_blank" href="{{ route('venue', $venue['id']) }}">{{ $venue['name'] }}</a></li>
                @endforeach
              </ul>
            </div>
          @endif
        </div>

        <div id="bands">
          @php $bandTypes = json_decode($promoter->band_types); @endphp
          @php $genres = json_decode($promoter->genre); @endphp
          @if (!$bandTypes)
            <p>We don't have any specific band types listed, please contact us if you would like to enquire about
              booking your band.
            </p>
          @else
            <p>The band types that we specialise in promoting are:</p>
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

                    @default
                  @endswitch
                @endforeach
              </ul>
            @endif

            @if ($promoter->genre)
              @php
                $genreCount = count($genres);
                $genreSuffix = $genreCount > 1 ? 's' : '';
              @endphp
              <p class="pt-2">We specialize in working with artists in the following genre{{ $genreSuffix }}:</p>
              <ul class="genre-list mb-2">
                @foreach ($genres as $genre)
                  <li class="ml-6">{{ $genre }}</li>
                @endforeach
              </ul>
            @endif
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
              @foreach ($promoter->recentReviews as $review)
                <div class="review text-center font-sans">
                  <p class="flex flex-col">"{{ $review->review }}" <span>- {{ $review->author }}</span></p>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Review Modal --}}
    <!-- Main modal -->
    <div id="review-modal" tabindex="-1" aria-hidden="true"promoter
      class="max-h-full fixed left-0 right-0 top-0 z-50 flex hidden h-[calc(100%-1rem)] w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0">
      <div class="max-h-full relative m-4 mx-auto w-full max-w-2xl border border-white">
        <!-- Modal content -->
        <div class="review-popup relative rounded-lg">
          <!-- Modal header -->
          <div class="dark:white flex items-center justify-between rounded-t border-b p-4 md:p-5">
            <h3 class="text-xl font-semibold">
              Leave a review for {{ $promoter->name }}
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
            <form action="{{ route('submit-promoter-review', $promoter->id) }}" method="POST">
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
      $(".promoter-tab-content > div:not(:first)").hide();

      // Add active class to the default tab link
      $(".tabLinks:first").addClass(
        "active text-blue-600 border-b-2 border-blue-600 rounded-t-lg dark:text-blue-500 dark:border-blue-500 group"
      );

      // Add click event to tab links
      $(".tabLinks").click(function() {
        // Get the tab ID from the data attribute
        var tabId = $(this).data("tab");

        // Hide all tab contents
        $(".promoter-tab-content > div").hide();

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
  </script>
