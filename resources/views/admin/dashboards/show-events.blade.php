<x-app-layout :dashboardType="$dashboardType" :modules="$modules">
  <x-slot name="header">
    <x-sub-nav :userId="$userId" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg border border-white bg-yns_dark_gray text-white">
        <div class="header border-b border-b-white px-8 pt-8">
          <div class="flex flex-row justify-between">
            <h1 class="font-heading text-4xl font-bold">My Events</h1>
            @if ($dashboardType != 'standard')
              <a href="{{ route('admin.dashboard.create-new-event', ['dashboardType' => $dashboardType]) }}"
                class="rounded-lg bg-white px-4 py-2 text-black transition duration-300 hover:bg-gradient-to-t hover:from-yns_dark_orange hover:to-yns_yellow">New
                Event</a>
            @else
              <a href="{{ route('gig-guide') }}"
                class="rounded-lg bg-white px-4 py-2 text-black transition duration-300 hover:bg-gradient-to-t hover:from-yns_dark_orange hover:to-yns_yellow">Find
                Events</a>
            @endif
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
              @if ($initialUpcomingEvents && $initialUpcomingEvents->isNotEmpty())
                @foreach ($initialUpcomingEvents as $event)
                  @include('admin.dashboards.partials.event_card', ['event' => $event])
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
                  @include('admin.dashboards.partials.event_card', ['event' => $event])
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
        </div>
      </div>
    </div>
  </div>
  </div>
</x-app-layout>
<script>
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
  jQuery('#past-tab').on('click', function() {
    jQuery('#load-more-upcoming').addClass('hidden');
    jQuery('#load-more-past').removeClass('hidden');
  });

  jQuery('#upcoming-tab').on('click', function() {
    jQuery('#load-more-past').addClass('hidden');
    jQuery('#load-more-upcoming').removeClass('hidden');
  });

  // Load More Upcoming Events
  jQuery('#load-more-upcoming').on('click', function(e) {
    e.preventDefault(); // Prevent the default button action if it's a button

    upcomingPage++; // Increment the page number for the next load
    console.log('Loading page:', upcomingPage);

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      url: "{{ route('admin.dashboard.load-more-upcoming-events', ['dashboardType' => $dashboardType]) }}",
      type: "GET",
      data: {
        page: upcomingPage
      },
      success: function(data) {
        jQuery('#upcoming-events .grid').append(data.html);
        if (!data.hasMorePages) {
          jQuery('#load-more-upcoming').hide();
        }
      },
      error: function(xhr) {
        showFailureNotification("An error occurred while loading events.");
      }
    });
  });

  // Load More Past Events
  jQuery('#load-more-past').on('click', function(e) {
    e.preventDefault();
    upcomingPage++;

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      url: "{{ route('admin.dashboard.load-more-past-events', ['dashboardType' => $dashboardType]) }}",
      type: "GET",
      data: {
        page: upcomingPage
      },
      success: function(data) {
        jQuery('#upcoming-events .grid').append(data.html);

        if (!data.hasMorePages) {
          jQuery('#load-more-past').hide();
        }
      },
      error: function(xhr) {
        showFailureNotification("An error occurred while loading events.");
      }
    });
  });



  // Delete Event
  jQuery(document).on('click', '.delete-event', function() {
    const eventId = jQuery(this).data('id'); // Get event ID from data attribute
    console.log(eventId);
    // Show confirmation notification
    showConfirmationNotification({
      text: 'Are you sure you want to delete this event?'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: `/dashboard/promoter/events/${eventId}`, // Your delete route
          type: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            showSuccessNotification(response.message); // Assuming response has a message property
            console.log(eventId);
            jQuery(`.event-card[data-id="${eventId}"]`)
          .remove(); // This line should work if the ID matches
          },
          error: function(xhr) {
            showFailureNotification(xhr.responseJSON.message ||
              'An error occurred while deleting the event.');
          }
        });

        jQuery(`.event-card[data-id="${eventId}"]`).fadeOut(300, function() {
          jQuery(this).remove(); // Remove the element after the fade-out effect
        });
      }
    });
  });
</script>
