<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">
      {{ __('Other') }}
    </h1>
  </x-slot>

  <div class="other-wrapper min-w-screen-xl relative mx-auto my-6 w-full max-w-screen-xl p-8">
    <div class="header flex gap-4">
      @if ($singleService->logo_url)
        <img src="{{ asset($singleService->logo_url) }}" alt="{{ $singleService->name }} Logo" class="other-logo">
      @endif
      <div class="header-text flex flex-col justify-center gap-2">
        <h1 class="text-sans text-4xl text-white">{{ $singleService->name }}</h1>
        <p class="font-sans text-2xl text-white">{{ $singleService->postal_town }}</p>
        <div class="socials-wrapper flex flex-row gap-4">
          @if ($singleService->contact_number || $singleService->contact_email || $singleService->contact_link ?? 'N/A')
            @if ($singleService->contact_number)
              <a class="hover:text-white" href="tel:{{ $singleService->contact_number }}"><span
                  class="fas fa-phone"></span></a>
            @endif
            @if ($singleService->contact_email)
              <a class="hover:text-white" href="mailto:{{ $singleService->contact_email }}"><span
                  class="fas fa-envelope"></span></a>
            @endif
            @if ($singleService->platforms)
              @foreach ($singleService->platforms as $platform)
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
            <a href="#" data-tab="packages"
              class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:border-gray-300 hover:text-gray-600 dark:hover:text-gray-300">
              <span class="fas fa-pound-sign mr-2"></span>Packages & Costs
            </a>
          </li>
          <li class="tab me-2">
            <a href="#" data-tab="environment-types"
              class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:border-gray-300 hover:text-gray-600 dark:hover:text-gray-300">
              <span class="fas fa-sun mr-2"></span>Environment Types
            </a>
          </li>
          <li class="tab me-2">
            <a href="#" data-tab="hours"
              class="tabLinks group inline-flex items-center justify-center rounded-t-lg border-b-2 border-transparent text-lg text-white hover:border-gray-300 hover:text-gray-600 dark:hover:text-gray-300">
              <i class="fas fa-clock mr-2"></i>Working Hours
            </a>
          </li>
        </ul>
      </div>

      <div class="other-tab-content mt-4 overflow-auto font-sans text-lg text-white">
        <div id="about">
          @if (!$singleService->decription)
            <p>We're still working on this! Come back later to read about us!</p>
          @else
            <p>{{ $singleService->description }}</p>
          @endif
        </div>

        <div id="packages" class="max-h-80 flex h-full flex-col gap-4 overflow-auto">
          @php $packages = json_decode($singleService->packages); @endphp
          @if (!$packages)
            <p>We don't have any packages listed yet, please contact us if you would like to enquire about costs.</p>
          @else
            <div class="packages-wrapper">
              @foreach ($packages as $package)
                <div class="package">
                  {{ $package->title }}
                </div>
              @endforeach
            </div>
          @endif
        </div>

        <div id="environment-types">
        </div>

        <div id="hours">
        </div>
      </div>
    </div>
  </div>
</x-guest-layout>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  $(document).ready(function() {
    // Hide all tab contents except the first one
    $(".other-tab-content > div:not(:first)").hide();

    // Add active class to the default tab link
    $(".tabLinks:first").addClass(
      "active text-blue-600 border-b-2 border-blue-600 rounded-t-lg dark:text-blue-500 dark:border-blue-500 group"
    );

    // Add click event to tab links
    $(".tabLinks").click(function() {
      // Get the tab ID from the data attribute
      var tabId = $(this).data("tab");

      // Hide all tab contents
      $(".other-tab-content > div").hide();

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
