<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">Andy's Gig Guide</h1>
  </x-slot>
  <div class="mx-auto min-h-screen w-full max-w-screen-2xl pt-32">
    <div class="group text-center">
      <h1 class="py-8 font-heading text-6xl text-white">Andy's Gig Guide</h1>
      <p class="font-sans text-white">Back in the day, Andy Jennings would create a Gig Guide on his Facebook page,
        spending time collecting,
        organizing, and listing weekly gigs up and down the country to help promote local music. This was hugely
        appreciated by many people and was often one of the first places they would hear about gigs in their local area.
      </p>
      <p class="font-sans text-white">Andy no longer makes these gig guides sadly, as you can imagine, it’s very
        time-consuming and a lot of work to do
        entirely for free.</p>
      <p class="font-sans text-white">As a thank you, we have dedicated our gig guide to Andy and recreated it in a
        similar format (with a few
        YNS tweaks).</p>
    </div>

    <!-- Gigs Table -->
    <div class="py-8">
      <div class="relative z-0 overflow-x-auto">
        <div class="flex items-center justify-around border border-white bg-black py-4 text-center">
          <div class="group">
            <label for="distance" class="text-lg text-white">Show gigs within:</label>
            <x-select id="distance" name="distance" :options="[
                '5' => '5 miles',
                '10' => '10 miles',
                '20' => '20 miles',
                '50' => '50 miles',
                'all' => 'Show all',
            ]" :selected="['5']" />
          </div>
          <div class="group flex items-center gap-2">
            <x-input-label class="mb-0">Show Other Gigs</x-input-label>
            <x-input-checkbox id="show-other-gigs" name="showOtherGigs"></x-input-checkbox>
          </div>
        </div>
        <table class="w-full border border-white text-left font-sans rtl:text-right">
          <thead class="text-white underline dark:bg-black">
            <tr>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Event
              </th>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Location
              </th>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Approx
                Distance
              </th>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Date
              </th>
            </tr>
          </thead>
          <tbody id="gigsTableBody">
            @if ($gigsCloseToMe)
              @foreach ($gigsCloseToMe as $event)
                <p>Event: {{ $event->event_name ?? 'No name' }}</p> <!-- Debugging output for event -->
                @include('partials.gigs_table_row', ['event' => $event, 'distance' => '5 miles']) <!-- Example for 5 miles -->
              @endforeach
            @endif
            {{-- @if ($otherGigs)
              @foreach ($otherGigs as $event)
                <p>Event: {{ $event->event_name ?? 'No name' }}</p> <!-- Debugging output for event -->
                @include('partials.gigs_table_row', ['event' => $event, 'distance' => 'All']) <!-- Example for "All" gigs -->
              @endforeach
            @endif --}}
          </tbody>
        </table>
      </div>
    </div>
  </div>
</x-guest-layout>
<script>
  let userLatitude = {{ $user->latitude ?? 'null' }};
  let userLongitude = {{ $user->longitude ?? 'null' }};
  let distance = document.getElementById('distance').value;

  function getUserLocation(callback) {
    if (userLatitude && userLongitude) {
      console.log("Using stored location: Lat " + userLatitude + ", Long " + userLongitude);
      callback(userLatitude, userLongitude);
    } else {
      console.log("No stored location. Attempting to get geolocation from the browser...");
      if (navigator.geolocation) {
        console.log('hit');
        navigator.geolocation.getCurrentPosition(
          function(position) {
            console.log('hit2');
            userLatitude = position.coords.latitude;
            userLongitude = position.coords.longitude;
            console.log("Geolocation obtained: Lat " + userLatitude + ", Long " + userLongitude);
            callback(userLatitude, userLongitude);
          },
          function(error) {
            console.log('Error handler hit');
            switch (error.code) {
              case error.PERMISSION_DENIED:
                alert("User denied the request for Geolocation.");
                break;
              case error.POSITION_UNAVAILABLE:
                alert("Location information is unavailable.");
                break;
              case error.TIMEOUT:
                alert("The request to get user location timed out.");
                break;
              case error.UNKNOWN_ERROR:
                alert("An unknown error occurred.");
                break;
            }
          }
        );
      } else {
        alert("Geolocation is not supported by this browser.");
      }
    }
  }


  function fetchGigs(distance, latitude, longitude) {
    fetch(`/gigs/filter?distance=${distance}&latitude=${latitude}&longitude=${longitude}`)
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          console.error(data.error);
          return;
        }

        const tableBody = document.getElementById('gigsTableBody');
        tableBody.innerHTML = '';

        data.gigsCloseToMe.forEach(event => {
          tableBody.innerHTML += createRow(event, `${event.distance} miles`);
        });

        if (data.otherGigs) {
          data.otherGigs.forEach(event => {
            tableBody.innerHTML += createRow(event, 'All');
          });
        }
      })
      .catch(error => console.error('Error fetching gigs:', error));
  }

  getUserLocation((lat, lon) => fetchGigs(distance, lat, lon));
</script>
