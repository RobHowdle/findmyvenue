<x-app-layout :dashboardType="$dashboardType" :modules="$modules">
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
      {{ __('Profile') }}
    </h2>
  </x-slot>

  <div x-data="{ selected: 1, open: false, selectedTab: 1 }" class="grid min-h-screen grid-cols-[1fr,2fr] gap-4">
    <div class="h-full bg-opac_8_black">
      <div class="py-8 font-heading text-xl">
        <p class="mb-8 px-8 font-bold">Settings</p>
        <div class="flex flex-col items-start gap-4">
          <button @click="selected = 1; selectedTab = 1"
            :class="{ 'bg-gradient-button': selected === 1, 'bg-yns_dark_gray': selected !== 1 }"
            class="group relative w-full px-8 py-2 text-left text-white transition duration-150 ease-in-out">
            <span class="absolute inset-0 transition-opacity duration-300 ease-in-out"
              :class="{ 'opacity-100': selected === 1, 'opacity-0': selected !== 1 }"></span>
            <span class="relative z-10">User</span>
          </button>

          @if ($dashboardType == 'promoter')
            @include('profile.partials.promoter-profile', [
                'promoterData' => $promoterData,
            ])
          @elseif($dashboardType == 'band')
            @include('profile.partials.band-profile', [
                'bandData' => $bandData,
            ])
          @endif

          <button @click="selected = 8; selectedTab = 8" data-tab="calendar"
            :class="{ 'bg-gradient-button': selected === 8, 'bg-yns_dark_gray': selected !== 8 }"
            class="group relative w-full px-8 py-2 text-left text-white transition duration-150 ease-in-out">
            <span class="absolute inset-0 transition-opacity duration-300 ease-in-out"
              :class="{ 'opacity-100': selected === 8, 'opacity-0': selected !== 8 }"></span>
            <span class="relative z-10">Calendar</span>
          </button>

          <button @click="selected = 9; selectedTab =9"
            :class="{ 'bg-gradient-button': selected === 9, 'bg-yns_dark_gray': selected !== 9 }"
            class="group relative w-full px-8 py-2 text-left text-white transition duration-150 ease-in-out">
            <span class="absolute inset-0 transition-opacity duration-300 ease-in-out"
              :class="{ 'opacity-100': selected === 9, 'opacity-0': selected !== 9 }"></span>
            <span class="relative z-10">Settings</span>
          </button>
        </div>
      </div>
    </div>
    <div class="mx-4 my-4 h-auto self-center font-heading">
      <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
        <div x-show="selectedTab === 1" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
          <div class="max-w-xl">
            <p class="text-xl font-bold">User Settings</p>
            @include('profile.partials.edit-user-details', [
                'userRole' => $userRole,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'location' => $location,
            ])
          </div>
        </div>
        @if ($dashboardType == 'promoter')
          @include('profile.partials.promoter-profile-tabs', [
              'promoterData' => $promoterData,
          ])
        @elseif($dashboardType == 'band')
          @include('profile.partials.band-profile-tabs', [
              'bandData' => $bandData,
          ])
        @endif

        <div x-show="selectedTab === 8" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
          <div class="w-full">
            <div class="flex items-center justify-center">
              <div class="group">
                @if (Auth::user()->google_access_token)
                  <p>Your Google Calendar is linked!</p>
                  <form action="{{ route('google.unlink') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-danger btn">Unlink Google Calendar</button>
                  </form>
                @else
                  <a href="{{ route('google.redirect') }}" class="btn btn-primary">Link Google Calendar</a>
                @endif
              </div>
              <div class="group">
                <form action="{{ route('google.sync') }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-success">Manual Google Sync</button>
                </form>
              </div>
              <div class="group">
                <button id="sync-all-events-apple"
                  class="rounded bg-green-500 px-4 py-2 font-semibold text-white hover:bg-green-600"
                  title="Sync All Events to Apple Calendar">
                  Sync All Events to Apple Calendar
                </button>
              </div>
            </div>
            <div id="calendar" data-user-id="{{ Auth::check() ? Auth::user()->id : '' }}"></div>
          </div>
        </div>
        <div x-show="selectedTab === 9" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
          <div class="w-full">
            <div class="group">
              @include('profile.partials.settings', [
                  'modules' => $modules,
              ])
            </div>
          </div>
        </div>

      </div>
    </div>
</x-app-layout>

<style>
  [x-cloak] {
    display: none;
  }
</style>
<script>
  document.getElementById('sync-all-events-apple').addEventListener('click', function() {
    var calendarEl = document.getElementById("calendar");
    var userId = calendarEl.getAttribute("data-user-id");
    const url = `/profile/events/${userId}/apple/sync`; // Define your route for syncing

    // Show loading state if needed
    this.textContent = 'Syncing...';

    // Make an AJAX request to trigger the download
    fetch(url)
      .then(response => {
        if (response.ok) {
          return response.blob(); // Return blob data for the .ics file
        }
        throw new Error('Network response was not ok.');
      })
      .then(blob => {
        // Create a link element to download the file
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'events.ics'; // Set a name for the file
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(url);

        // Reset button text
        this.textContent = 'Sync All Events to Apple Calendar';
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Failed to sync events. Please try again.');
        this.textContent = 'Sync All Events to Apple Calendar';
      });
  });
</script>
