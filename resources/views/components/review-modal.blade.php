<div id="review-modal" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 flex hidden items-center justify-center">
  <div class="relative mx-auto w-full max-w-4xl border border-white bg-white dark:bg-black">
    <div class="review-popup relative rounded-lg bg-white dark:bg-black">
      <div class="flex items-center justify-between rounded-t border-b p-4 md:p-5">
        <h3 class="text-xl font-semibold">
          Leave a review
        </h3>
        <button type="button" data-modal-hide="review-modal" class="text-white hover:text-ynsLightGray">
          <span class="fas fa-times"></span>
          <span class="sr-only">Close modal</span>
        </button>
      </div>
      <div class="p-4 md:p-5">
        <form action="{{ route($route, $profileId) }}" method="POST">
          @csrf
          <!-- Add rating blocks dynamically -->
          @foreach (['communication', 'rate_of_pay', 'promotion', 'gig_quality'] as $ratingType)
            <div class="rating-block grid grid-cols-2">
              <p>{{ ucfirst(str_replace('_', ' ', $ratingType)) }}:</p>
              <div class="rating">
                @for ($i = 1; $i <= 5; $i++)
                  <input type="checkbox" name="{{ $ratingType }}-rating[]" value="{{ $i }}"
                    id="{{ $ratingType }}-rating-{{ $i }}" class="rating-icon" />
                  <label for="{{ $ratingType }}-rating-{{ $i }}" class="rating-label"></label>
                @endfor
              </div>
            </div>
          @endforeach

          <div class="mt-4">
            <x-input-label for="review_author" :value="__('Your Name')" />
            <x-text-input name="review_author" id="review_author" class="mt-1 block w-full" required
              autocomplete="name" />
          </div>
          <div class="mt-4">
            <x-input-label for="review_message" :value="__('Your Review')" />
            <x-textarea-input name="review_message" id="review_message" class="mt-1 block w-full" required required />
          </div>
          <button type="submit"
            class="mt-4 rounded bg-gradient-to-t from-ynsDarkOrange to-ynsYellow px-6 py-2 text-white">Submit
            Review</button>
        </form>
      </div>
    </div>
  </div>
</div>
