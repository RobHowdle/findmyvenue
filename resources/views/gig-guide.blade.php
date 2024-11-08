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
  // Define userLatitude and userLongitude globally
  let userLatitude = {{ $user->latitude ?? 'null' }};
  let userLongitude = {{ $user->longitude ?? 'null' }};
  let distance = document.getElementById('distance').value; // Default value for distance filter

  // Function to format the event date and time
  function formatDateTime(eventDate, eventStartTime) {
    // Ensure both eventDate and eventStartTime are provided
    if (!eventDate || !eventStartTime) {
      return 'Invalid Date/Time';
    }

    // Convert eventDate to a Date object
    const datePart = new Date(eventDate);

    // Check if datePart is valid
    if (isNaN(datePart)) {
      return 'Invalid Date';
    }

    // Format the date as DD-MM-YYYY
    const formattedDate = datePart.toLocaleDateString('en-GB', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric'
    });

    const timePart = eventStartTime.split(':').slice(0, 2).join(':');

    // Combine formatted date with event start time
    const formattedDateTime = `${formattedDate} @ ${timePart}`;

    return formattedDateTime;
  }

  // Function to generate row HTML for the gigs table
  function createRow(event, distanceLabel) {
    const eventDate = event.event_date || 'No Date';
    const eventTime = event.event_start_time || '00:00'; // Default time if not provided

    // Format the date and time
    const formattedDateTime = formatDateTime(eventDate, eventTime);

    return `
      <tr class="odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-black even:dark:bg-gray-900">
        <td class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">${event.event_name || 'No name'}</td>
        <td class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">${event.location || 'No Location'}</td>
        <td class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">${event.distance ? event.distance.toFixed(2) : 'No Distance'} miles</td>
        <td class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">${formattedDateTime || 'No Date/Time'}</td>
      </tr>
    `;
  }

  document.getElementById('distance').addEventListener('change', function() {
    distance = this.value; // Update distance whenever the selection changes
    console.log("Selected Distance:", distance);

    // If latitude and longitude are not available from the user profile, use geolocation
    if (!userLatitude || !userLongitude) {
      console.log("Latitude and Longitude are not stored in the user profile. Attempting to get geolocation...");
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          userLatitude = position.coords.latitude;
          userLongitude = position.coords.longitude;
          console.log("Geolocation obtained: Lat " + userLatitude + ", Long " + userLongitude);
          fetchGigs(distance, userLatitude, userLongitude);
        }, function() {
          alert("Unable to retrieve your location.");
        });
      } else {
        alert("Geolocation is not supported by this browser.");
      }
    } else {
      console.log("Using stored location: Lat " + userLatitude + ", Long " + userLongitude);
      fetchGigs(distance, userLatitude, userLongitude);
    }
  });

  document.getElementById('show-other-gigs').addEventListener('change', function() {
    const showOtherGigs = this.checked; // Whether the checkbox is checked
    console.log('Show other gigs:', showOtherGigs);

    // Capture the necessary parameters for the request
    fetch(
        `/gigs/filter?distance=${distance}&latitude=${userLatitude}&longitude=${userLongitude}&showOtherGigs=${showOtherGigs}`
      )
      .then(response => response.json())
      .then(data => {
        console.log('Fetched data:', data); // Debugging output to check response

        // Clear existing rows before populating new data
        const tableBody = document.getElementById('gigsTableBody');
        tableBody.innerHTML = '';

        // Add gigs within the selected distance
        data.gigsCloseToMe.forEach(event => {
          tableBody.innerHTML += createRow(event, `${event.distance} miles`);
        });

        // Only add "other gigs" if the checkbox is checked
        if (showOtherGigs) {
          console.log('Adding other gigs...');
          data.otherGigs.forEach(event => {
            tableBody.innerHTML += createRow(event, 'All');
          });
        }
      })
      .catch(error => {
        console.error('Error fetching gigs:', error);
        alert('There was an error fetching the gigs. Please try again.');
      });
  });

  // Initialize the page with the default gigs
  fetchGigs(distance, userLatitude, userLongitude);

  function fetchGigs(distance, userLatitude, userLongitude) {
    // Make the AJAX request to the filterGigs route
    fetch(`/gigs/filter?distance=${distance}&latitude=${userLatitude}&longitude=${userLongitude}`)
      .then(response => response.json()) // Parse JSON from the response
      .then(data => {
        console.log(data);
        // Check for errors in the response
        if (data.error) {
          console.error(data.error);
          return;
        }

        const tableBody = document.getElementById('gigsTableBody');
        tableBody.innerHTML = ''; // Clear existing rows

        // Populate table with gigs within the selected distance
        data.gigsCloseToMe.forEach(event => {
          tableBody.innerHTML += createRow(event, `${event.distance} miles`);
        });

        // Populate table with other gigs
        if (data.otherGigs) {
          data.otherGigs.forEach(event => {
            tableBody.innerHTML += createRow(event, 'All');
          });
        }
      })
      .catch(error => console.error('Error fetching gigs:', error));
  }
</script>
