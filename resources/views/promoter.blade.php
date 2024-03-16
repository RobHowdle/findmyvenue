<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">
      {{ __('Promoters') }}
    </h1>
  </x-slot>

  <div class="promoter-wrapper py-8">
    <div class="wrapper mx-auto grid px-8">
      <div class="wrapper-header col-span-1 row-start-1 row-end-2 pr-8">
        <img src="{{ asset($promoter->logo_url) }}" alt="{{ $promoter->name }} Logo" class="promoter-logo">

        <div class="text-wrapper flex flex-col gap-3">
          <h1 class="text-left font-heading text-4xl text-white">{{ $promoter->name }}</h1>
          <p class="font-sans text-2xl text-white">{{ $promoter->location }}</p>
          <div class="socials-wrapper flex flex-row gap-4">
            @if ($promoter->contact_number || $promoter->contact_email || $promoter->contact_link ?? 'N/A')
              @if ($promoter->contact_number)
                <a href="tel:{{ $promoter->contact_number }}"><span class="fas fa-phone"></span></a>
              @endif
              @if ($promoter->contact_email)
                <a href="mailto:{{ $promoter->contact_email }}"><span class="fas fa-envelope"></span></a>
              @endif
              @if ($promoter->platforms)
                @foreach ($promoter->platforms as $platform)
                  @if ($platform['platform'] == 'facebook')
                    <a href="{{ $platform['url'] }}" target=_blank><span class="fab fa-facebook"></span></a>
                  @elseif($platform['platform'] == 'twitter')
                    <a href="{{ $platform['url'] }}" target=_blank><span class="fab fa-twitter"></span></a>
                  @elseif($platform['platform'] == 'instagram')
                    <a href="{{ $platform['url'] }}" target=_blank><span class="fab fa-instagram"></span></a>
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
            <button class="text-sm">Worked with me? <span data-modal-target="review-modal"
                data-modal-toggle="review-modal" type="button">Leave Me A Review</span></button>
          </div>
        </div>
      </div>
      <div class="wrapper-body col-span-1 row-start-2 row-end-3 mt-4 overflow-x-auto pr-8 shadow-md sm:rounded-lg">
        <h2 class="font-sans text-2xl underline">About Me</h2>
        <p class="pt-2 font-sans text-xl">{{ $promoter->about_me }}</p>
        <h3 class="mt-4 font-sans text-2xl underline">My Venues</h3>
        <p class="pt-2 font-sans text-xl">{{ $promoter->my_venues }}</p>
        <h4 class="mt-4 font-sans text-2xl underline">Where can you find me?</h4>
        @foreach ($promoter->venues as $venue)
          <p class="pt-2 font-sans text-xl">{{ $venue['name'] }}</p>
        @endforeach
      </div>

      <div class="col-start-2 col-end-3 row-span-3 border-l-2 border-white pl-8">
        <h4 class="font-sans text-2xl underline">My Reviews ({{ $reviewCount }})</h4>
        <div class="ratings-block mt-4 flex flex-col gap-4">
          <p class="grid grid-cols-2">Communication:
            <span class="flex flex-row gap-3">
              @php
                $fullIcons = floor($averageCommunicationRating); // Number of full icons
                $fraction = $averageCommunicationRating - $fullIcons; // Fractional part of the rating
              @endphp
              @for ($i = 0; $i < $fullIcons; $i++)
                <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
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
                <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
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
                <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
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
                <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
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

    {{-- Review Modal --}}
    <!-- Main modal -->
    <div id="review-modal" tabindex="-1" aria-hidden="true"
      class="fixed left-0 right-0 top-0 z-50 flex hidden h-[calc(100%-1rem)] max-h-full w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0">
      <div class="relative m-4 mx-auto max-h-full w-full max-w-2xl border border-white">
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

  </div>
</x-guest-layout>
{{-- 
@push('scripts')
  {{ $dataTable->scripts() }} 
@endpush --}}
<script>
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
