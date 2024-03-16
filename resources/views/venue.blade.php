<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">
      {{ __('Venues') }}
    </h1>
  </x-slot>

  <div class="venue-wrapper px-8 py-16">
    <div class="header flex flex-col gap-2">
      @if ($venue->logo_url)
        <img src="{{ asset($venue->logo_url) }}" alt="{{ $venue->name }} Logo" class="venue-logo">
      @endif
      <h1 class="text-sans text-4xl text-white">{{ $venue->name }}</h1>
      <p class="font-sans text-2xl text-white">{{ $venue->postal_town }}</p>
      <div class="socials-wrapper flex flex-row gap-4">
        @if ($venue->contact_number || $venue->contact_email || $venue->contact_link ?? 'N/A')
          @if ($venue->contact_number)
            <a href="tel:{{ $venue->contact_number }}"><span class="fas fa-phone"></span></a>
          @endif
          @if ($venue->contact_email)
            <a href="mailto:{{ $venue->contact_email }}"><span class="fas fa-envelope"></span></a>
          @endif
          @if ($venue->platforms)
            @foreach ($venue->platforms as $platform)
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
    </div>
    <div class="body">
      <div class="h-auto py-4">
        <ul
          class="flex flex-wrap border-b border-gray-200 text-center text-sm font-medium text-gray-500 dark:border-gray-700 dark:text-gray-400">
          <li class="tab me-2 pl-0">
            <a href="#" data-tab="about"
              class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent hover:border-gray-300 hover:text-gray-600 dark:hover:text-gray-300">
              <span class="fas fa-info-circle mr-2"></span>About
            </a>
          </li>
          <li class="tab me-2">
            <a href="#" data-tab="in-house-gear"
              class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent hover:border-gray-300 hover:text-gray-600 dark:hover:text-gray-300">
              <span class="fas fa-cogs mr-2"></span>In House Gear
            </a>
          </li>
          <li class="tab me-2">
            <a href="#" data-tab="band-types"
              class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent hover:border-gray-300 hover:text-gray-600 dark:hover:text-gray-300">
              <svg
                class="me-2 h-4 w-4 text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300"
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path
                  d="M5 11.424V1a1 1 0 1 0-2 0v10.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.228 3.228 0 0 0 0-6.152ZM19.25 14.5A3.243 3.243 0 0 0 17 11.424V1a1 1 0 0 0-2 0v10.424a3.227 3.227 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.243 3.243 0 0 0 2.25-3.076Zm-6-9A3.243 3.243 0 0 0 11 2.424V1a1 1 0 0 0-2 0v1.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0V8.576A3.243 3.243 0 0 0 13.25 5.5Z" />
              </svg>Band Types
            </a>
          </li>
          <li class="tab me-2">
            <a href="#" data-tab="genres"
              class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent hover:border-gray-300 hover:text-gray-600 dark:hover:text-gray-300">
              <svg
                class="me-2 h-4 w-4 text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300"
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                <path
                  d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z" />
              </svg>Genres
            </a>
          </li>
          <li class="tab me-2">
            <a href="#" data-tab="reviews"
              class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent hover:border-gray-300 hover:text-gray-600 dark:hover:text-gray-300">
              <span class="fas fa-star mr-2"></span> Reviews
            </a>
          </li>
        </ul>
      </div>

      <div class="venue-tab-content mt-4 overflow-auto">
        <div id="about">
          <p>{{ $venue->description }}</p>
        </div>
        <div id="in-house-gear" class="flex flex-col gap-4">
          <div class="gear-block flex flex-col">
            <p class="text-lg uppercase text-white">Stage:</p>
            <span class="text-base text-white">Size: 12ft x 28ft x 2ft</span>
          </div>

          <div class="gear-block flex flex-col">
            <p class="text-lg uppercase text-white">Lighting</p>
            <span class="text-base text-white">Rig: LightShark LS-1</span>
            <span class="text-base text-white">Console: GrandMA2</span>
          </div>

          <div class="gear-block flex flex-col">
            <p class="text-lg uppercase text-white">Sound:</p>
            <span class="text-base text-white">Desk: Yamaha M7CL-48</span>
            <span class="text-base text-white">Monitors: Mackie SRM 450 active x9</span>
            <span class="text-base text-white">Microphones: Shure SM58 x12, Shure SM57 x8</span>
            <span class="text-base text-white">Guitar Gear: Marshall Valve State AVT2000 Combo, Laney LX65R Combo</span>
            <span class="text-base text-white">Bass Gear: Fender Rumble 100 Combo</span>
            <span class="text-base text-white">Drum Gear: Pearl Vision Drum Kit, Single Bass Drum Pedal, Hi-Hat Stand,
              Snare Stand,
              Boom Cymbal Stand,
              Straight Cymbal Stand, Drum Throne</span>

            <div class="gear-block flex flex-col">
              <span class="text-base text-white">Other Gear: Yamaha P45 Electric Piano, Trace Elliot 15" Bass Cab,
                Marshall
                4x12"
                Guitar
                Cab</span>
            </div>
          </div>
          <div class="gear-block flex flex-col">
            <p class="text-lg uppercase text-white">Stage:</p>
            <span class="text-base text-white">Size: 12ft x 28ft x 2ft</span>
          </div>

          <div class="gear-block flex flex-col">
            <p class="text-lg uppercase text-white">Lighting</p>
            <span class="text-base text-white">Rig: LightShark LS-1</span>
            <span class="text-base text-white">Console: GrandMA2</span>
          </div>

          <div class="gear-block flex flex-col">
            <p class="text-lg uppercase text-white">Sound:</p>
            <span class="text-base text-white">Desk: Yamaha M7CL-48</span>
            <span class="text-base text-white">Monitors: Mackie SRM 450 active x9</span>
            <span class="text-base text-white">Microphones: Shure SM58 x12, Shure SM57 x8</span>
            <span class="text-base text-white">Guitar Gear: Marshall Valve State AVT2000 Combo, Laney LX65R Combo</span>
            <span class="text-base text-white">Bass Gear: Fender Rumble 100 Combo</span>
            <span class="text-base text-white">Drum Gear: Pearl Vision Drum Kit, Single Bass Drum Pedal, Hi-Hat Stand,
              Snare Stand,
              Boom Cymbal Stand,
              Straight Cymbal Stand, Drum Throne</span>

            <div class="gear-block flex flex-col">
              <span class="text-base text-white">Other Gear: Yamaha P45 Electric Piano, Trace Elliot 15" Bass Cab,
                Marshall
                4x12"
                Guitar
                Cab</span>
            </div>
          </div>
          <div class="gear-block flex flex-col">
            <p class="text-lg uppercase text-white">Stage:</p>
            <span class="text-base text-white">Size: 12ft x 28ft x 2ft</span>
          </div>

          <div class="gear-block flex flex-col">
            <p class="text-lg uppercase text-white">Lighting</p>
            <span class="text-base text-white">Rig: LightShark LS-1</span>
            <span class="text-base text-white">Console: GrandMA2</span>
          </div>

          <div class="gear-block flex flex-col">
            <p class="text-lg uppercase text-white">Sound:</p>
            <span class="text-base text-white">Desk: Yamaha M7CL-48</span>
            <span class="text-base text-white">Monitors: Mackie SRM 450 active x9</span>
            <span class="text-base text-white">Microphones: Shure SM58 x12, Shure SM57 x8</span>
            <span class="text-base text-white">Guitar Gear: Marshall Valve State AVT2000 Combo, Laney LX65R
              Combo</span>
            <span class="text-base text-white">Bass Gear: Fender Rumble 100 Combo</span>
            <span class="text-base text-white">Drum Gear: Pearl Vision Drum Kit, Single Bass Drum Pedal, Hi-Hat Stand,
              Snare Stand,
              Boom Cymbal Stand,
              Straight Cymbal Stand, Drum Throne</span>

            <div class="gear-block flex flex-col">
              <span class="text-base text-white">Other Gear: Yamaha P45 Electric Piano, Trace Elliot 15" Bass Cab,
                Marshall
                4x12"
                Guitar
                Cab</span>
            </div>
          </div>

          <p class="text-lg uppercase text-white">Effects:</p>
          <span class="text-base text-white">List: Smoke Machine, Lasers, Projector</span>
        </div>
        <div id="band-types">
          <p>{{ $venue->band_type }}</p>
        </div>
        <div id="genres">
          <p>{{ $venue->genres }}</p>
        </div>
        <div id="reviews">
          Reviews
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
</script>
