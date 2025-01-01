<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">{{ $event->event_name }}</h1>
  </x-slot>
  <div class="mx-auto mt-32 w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg border border-white bg-yns_dark_gray text-white">
        <div class="header border-b border-b-white px-8 py-8">
          <div class="flex flex-row items-center justify-between">
            <div class="group">
              <h1 class="font-heading text-4xl font-bold">{{ $event->event_name }}</h1>
              <p class="text-xl">Date: {{ $event->event_date->format('jS F Y') }}</p>
              <div class="socials"></div>
            </div>
            <div class="group flex gap-x-4">
              @if (!$isPastEvent)
                <a href="{{ $event->ticket_url ? $event->ticket_url : '#' }}" target="_blank"
                  class="mb-4 rounded-lg bg-white px-4 py-2 text-black transition duration-300 hover:bg-gradient-to-t hover:from-yns_dark_orange hover:to-yns_yellow">
                  Tickets <span class="fas fa-ticket-alt ml-1"></span>
                </a>
                @auth
                  <a href="#" id="addToCalendarButton"
                    class="mb-4 rounded-lg bg-white px-4 py-2 text-black transition duration-300 hover:bg-gradient-to-t hover:from-yns_dark_orange hover:to-yns_yellow">
                    Add To Calendar <span class="fas fa-calendar-alt ml-1"></span>
                  </a>
                @endauth
              @endif
            </div>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-x-4 font-heading text-lg">
          <div class="col px-8 pt-8">
            <div class="group border-b border-white">
              <div class="group mb-4 text-center">
                <p class="flex flex-col text-2xl font-bold underline">Headliner</p>
                <a href="{{ route('singleService', ['serviceName' => 'Artist', 'serviceId' => $headliner->id]) }}"
                  class="font-normal no-underline transition duration-150 ease-in-out hover:text-yns_yellow">{{ $headliner->name ?? 'No Headliner' }}</a>
              </div>
              <div class="group mb-4 text-center">
                <p class="flex flex-col text-xl font-bold underline">Main Support</p>
                <a href="{{ route('singleService', ['serviceName' => 'Artist', 'serviceId' => $mainSupport->id]) }}"
                  class="font-normal no-underline transition duration-150 ease-in-out hover:text-yns_yellow">{{ $mainSupport->name ?? 'No Main Support' }}</a>
              </div>
              <div class="group mb-4 flex flex-col text-center">
                @if (count($otherBands) > 0)
                  <p class="text-lg font-bold underline">Band</p>
                  @foreach ($otherBands as $band)
                    <a href="{{ route('singleService', ['serviceName' => 'Artist', 'serviceId' => $band->id]) }}"
                      class="font-normal no-underline transition duration-150 ease-in-out hover:text-yns_yellow">{{ $band->name }}</a>
                  @endforeach
                @endif
              </div>
              <div class="group mb-4 text-center">
                <p class="text-md flex flex-col font-bold underline">Opener</p>
                <a href="{{ route('singleService', ['serviceName' => 'Artist', 'serviceId' => $opener->id]) }}"
                  class="font-normal no-underline transition duration-150 ease-in-out hover:text-yns_yellow">{{ $opener->name ?? 'No Opener' }}</a>
              </div>
            </div>
            @if ($event->event_description)
              <div class="group my-2 flex flex-row items-center justify-center text-center">
                <span class="fas fa-tag mr-2"></span>{{ $event->event_description }}
              </div>
            @endif
            <div class="group my-2 flex flex-row items-center justify-center">
              <span class="fas fa-clock mr-2"></span>{{ $eventStartTime }} @if ($eventEndTime)
                - {{ $eventEndTime }}
              @endif
            </div>
            <div class="group mb-2 flex flex-row items-start justify-center text-center">
              <span class="fas fa-map-marker-alt"></span>
              @forelse($event->venues as $venue)
                <a class="transition duration-150 ease-in-out hover:text-yns_yellow"
                  href="{{ route('venues', $venue->id) }}">{{ $venue->location }}</a>
              @empty
                No Venue Assigned
              @endforelse

            </div>
            <div class="group mb-2 flex flex-row items-start justify-center text-center">
              <span class="fas fa-bullhorn mr-2"></span>
              @forelse($event->promoters as $promoter)
                <a class="transition duration-150 ease-in-out hover:text-yns_yellow"
                  href="{{ route('promoters', $promoter->id) }}">{{ $promoter->name }}</a>
              @empty
                No Promoter Assigned
              @endforelse
            </div>

            <div class="group mb-2 flex flex-row items-center justify-center text-center">
              <span class="fas fa-ticket-alt mr-2"></span>{{ formatCurrency($event->on_the_door_ticket_price) }} O.T.D
            </div>
          </div>
          <div class="col relative place-content-center">
            <div
              class="absolute right-2 top-12 flex h-12 w-12 place-items-center justify-center rounded-50 bg-opac_8_black p-2 transition duration-150 ease-in-out hover:bg-opac_5_black">
              <span class="fas fa-search-plus"></span>
            </div>
            <img src="{{ asset($event->poster_url) }}" alt="{{ $event->event_name }} Poster"
              class="cursor-pointer object-cover transition duration-150 ease-in-out hover:opacity-75" id="eventPoster"
              onclick="openModal()">
            <div id="modal" class="fixed inset-0 hidden scale-95 transform justify-center duration-300 ease-in-out">
              <div class="mt-32 rounded-lg bg-white p-4">
                <span
                  class="absolute right-2 top-2 cursor-pointer text-white transition duration-150 ease-in-out hover:text-yns_yellow"
                  onclick="closeModal()"><span class="fas fa-times"></span></span>
                <img src="{{ asset($event->poster_url) }}" alt="Enlarged Event Poster" class="max-h-80 max-w-3xl" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-guest-layout>
<script>
  function openModal() {
    const modal = document.getElementById('modal');
    modal.classList.remove('hidden');
    setTimeout(() => {
      modal.classList.remove('opacity-0');
      modal.classList.add('opacity-100');
    }, 10);
  }

  function closeModal() {
    const modal = document.getElementById('modal');
    modal.classList.remove('opacity-100');
    modal.classList.add('opacity-0');

    // Hide the modal after the animation ends
    setTimeout(() => {
      modal.classList.add('hidden');
    }, 300);
  }
</script>
