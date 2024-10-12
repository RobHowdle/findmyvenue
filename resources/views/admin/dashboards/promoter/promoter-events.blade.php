<x-app-layout>
  <x-slot name="header">
    <x-promoter-sub-nav :promoter="$promoter" :promoterId="$promoter->id" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg border border-white bg-yns_dark_gray text-white">
        <div class="header border-b border-b-white px-8 pt-8">
          <div class="flex flex-row justify-between">
            <h1 class="font-heading text-4xl font-bold">My Events</h1>
            <a href="{{ route('admin.dashboard.promoter.create-new-event') }}"
              class="rounded-lg bg-white px-4 py-2 text-black transition duration-300 hover:bg-gradient-to-t hover:from-yns_dark_orange hover:to-yns_yellow">New
              Event</a>
          </div>

          <div class="mt-8 flex gap-x-8">
            <p id="upcoming-tab"
              class="cursor-pointer border-b-2 border-b-transparent pb-2 text-white transition duration-150 ease-in-out hover:border-b-yns_yellow hover:text-yns_yellow">
              Upcoming
              Events</p>
            <p id="past-tab"
              class="cursor-pointer border-b-2 border-b-transparent pb-2 text-white transition duration-150 ease-in-out hover:border-b-yns_yellow hover:text-yns_yellow">
              Past
              Events</p>
          </div>
        </div>

        <div id="tab-content" class="px-8 pt-8">
          <div id="upcoming-events" class="event-grid">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
              @if ($upcomingEvents && $upcomingEvents->isNotEmpty())
                @foreach ($upcomingEvents as $event)
                  @include('admin.dashboards.promoter.partials.event_card', ['event' => $event])
                @endforeach
              @else
                <p>No Upcoming Events Found</p>
              @endif
            </div>
          </div>

          <div id="past-events" class="event-grid hidden">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
              @if ($pastEvents && $pastEvents->isNotEmpty())
                @foreach ($pastEvents as $event)
                  @include('admin.dashboards.promoter.partials.event_card', ['event' => $event])
                @endforeach
              @else
                <p>No Past Events Found</p>
              @endif
            </div>
          </div>
        </div>

        <div class="mt-6 text-center">
          @if ($showLoadMoreUpcoming)
            <button id="load-more-upcoming"
              class="mb-4 rounded-lg bg-white px-4 py-2 text-black transition duration-300 hover:bg-gradient-to-t hover:from-yns_dark_orange hover:to-yns_yellow">
              Load More
            </button>
          @else
            <button id="load-more-upcoming" class="hidden"></button>
          @endif

          @if ($hasMorePast)
            <button id="load-more-past"
              class="mb-4 rounded-lg bg-white px-4 py-2 text-black transition duration-300 hover:bg-gradient-to-t hover:from-yns_dark_orange hover:to-yns_yellow">
              Load More
            </button>
          @else
            <button id="load-more-past" class="hidden"></button>
          @endif
        </div>
      </div>
    </div>
  </div>
  </div>
</x-app-layout>
<script>
  let upcomingOffset = 3;
  let pastOffset = 3;
  let upcomingPage = 1;

  document.getElementById('upcoming-tab').classList.add('border-b-yns_yellow', 'text-yns_yellow');
  document.getElementById('past-tab').classList.add('text-white');

  document.getElementById('upcoming-tab').addEventListener('click', function() {
    document.getElementById('upcoming-events').classList.remove('hidden');
    document.getElementById('past-events').classList.add('hidden');

    // Add active styles to Upcoming tab
    this.classList.add('border-b-yns_yellow', 'text-yns_yellow');
    this.classList.remove('text-white');

    // Remove active styles from Past tab
    document.getElementById('past-tab').classList.remove('border-b-yns_yellow', 'text-yns_yellow');
    document.getElementById('past-tab').classList.add('text-white');
  });

  document.getElementById('past-tab').addEventListener('click', function() {
    document.getElementById('past-events').classList.remove('hidden');
    document.getElementById('upcoming-events').classList.add('hidden');

    // Add active styles to Past tab
    this.classList.add('border-b-yns_yellow', 'text-yns_yellow');
    this.classList.remove('text-white');

    // Remove active styles from Upcoming tab
    document.getElementById('upcoming-tab').classList.remove('border-b-yns_yellow', 'text-yns_yellow');
    document.getElementById('upcoming-tab').classList.add('text-white');
  });

  // Load more past events
  $('#past-tab').on('click', function() {
    $('#load-more-upcoming').addClass('hidden');
    $('#load-more-past').removeClass('hidden');
  });

  $('#upcoming-tab').on('click', function() {
    $('#load-more-past').addClass('hidden');
    $('#load-more-upcoming').removeClass('hidden');
  });

  // Load More Upcoming Events
  $('#load-more-upcoming').on('click', function(e) {
    upcomingPage++; // Increment the page number for the next load
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      url: "{{ route('admin.dashboard.promoter.load-more-upcoming-events') }}",
      type: "GET",
      data: {
        page: upcomingPage // Send the updated page number
      },
      success: function(data) {
        console.log(data);
        $('#upcoming-events').append(data.html); // Append new events

        // Hide button if no more events
        if (!data.hasMorePages) {
          $('#load-more-upcoming').hide();
        }
      },
      error: function(xhr) {
        console.error(xhr);
        alert("An error occurred while loading events.");
      }
    });
  });


  // Load More Past Events
  $('#load-more-past').on('click', function() {
    $.ajax({
      url: "{{ route('admin.dashboard.promoter.load-more-past-events') }}",
      type: "GET",
      data: {
        offset: pastOffset
      },
      success: function(data) {
        $('#past-events .grid').append(data.html); // Append to the grid
        pastOffset += 3; // Increment offset for next request

        // Hide button if no more events
        if (!data.hasMorePages) {
          $('#load-more-past').hide();
        }
      },
      error: function(xhr) {
        console.error(xhr);
        alert("An error occurred while loading events.");
      }
    });
  });
</script>
