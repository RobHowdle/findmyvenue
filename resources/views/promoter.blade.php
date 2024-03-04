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
            <p>Ratings (69): </p>
            <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
            <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
            <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
            <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
            <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
          </div>
        </div>
      </div>
      {{-- {{ $dataTable->table() }} --}}
      <div class="wrapper-body col-span-1 row-start-2 row-end-3 mt-4 overflow-x-auto pr-8 shadow-md sm:rounded-lg">
        <h2 class="font-sans text-2xl underline">About Me</h2>
        <p class="pt-2 font-sans text-xl">{{ $promoter->about_me }}</p>
        <h3 class="mt-4 font-sans text-2xl underline">My Venues</h3>
        <p class="pt-2 font-sans text-xl">{{ $promoter->my_venues }}</p>
      </div>

      <div class="col-start-2 col-end-3 row-span-3 border-l-2 border-white pl-8">
        <h4 class="font-sans text-2xl underline">My Reviews</h4>
        <div class="ratings-block mt-4 flex flex-col gap-4">
          <p class="grid grid-cols-2">Communication:
            <span class="flex flex-row gap-3">
              <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
              <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
              <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
              <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
              <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
            </span>
          </p>
          <p class="grid grid-cols-2">Rate Of Pay:
            <span class="flex flex-row gap-3">
              <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
              <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
              <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
              <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
              <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
            </span>
          </p>
          <p class="grid grid-cols-2">Promotion:
            <span class="flex flex-row gap-3">
              <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
              <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
              <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
              <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
              <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
            </span>
          </p>
          <p class="grid grid-cols-2">Gig Quality:
            <span class="flex flex-row gap-3">
              <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
              <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
              <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
              <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
              <img src="https://img.icons8.com/emoji/48/sign-of-the-horns-emoji.png" alt="sign-of-the-horns-emoji" />
            </span>
          </p>

          <h5 class="text-sans mt-4">Overall Rating (69): 5/5</h5>
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
</x-guest-layout>
{{-- 
@push('scripts')
  {{ $dataTable->scripts() }} 
@endpush --}}
