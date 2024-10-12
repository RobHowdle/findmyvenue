<x-app-layout>
  <x-slot name="header">
    <x-promoter-sub-nav :promoter="$promoter" :promoterId="$promoter->id" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg border border-white bg-yns_dark_gray text-white">
        <div class="header border-b border-b-white px-8 py-8">
          <div class="flex flex-row items-center justify-between">
            <div class="group">
              <h1 class="font-heading text-4xl font-bold">{{ $event->name }}</h1>
              <p class="text-xl">Date: {{ $event->event_date->format('jS F Y') }}</p>
              <div class="socials"></div>
            </div>
            <div class="group flex gap-x-4">
              <a href="#" target="_blank"
                class="mb-4 rounded-lg bg-white px-4 py-2 text-black transition duration-300 hover:bg-gradient-to-t hover:from-yns_dark_orange hover:to-yns_yellow">
                Tickets <span class="fas fa-ticket-alt ml-1"></span>
              </a>
              <a href="#"
                class="mb-4 rounded-lg bg-white px-4 py-2 text-black transition duration-300 hover:bg-gradient-to-t hover:from-yns_dark_orange hover:to-yns_yellow">
                Add To Calendar <span class="fas fa-calendar-alt ml-1"></span>
              </a>
              <a href="#"
                class="mb-4 rounded-lg bg-white px-4 py-2 text-black transition duration-300 hover:bg-gradient-to-t hover:from-yns_dark_orange hover:to-yns_yellow">
                Edit <span class="fas fa-edit ml-1"></span>
              </a>
              <a href="#"
                class="mb-4 rounded-lg bg-white px-4 py-2 text-black transition duration-300 hover:bg-gradient-to-t hover:from-yns_dark_orange hover:to-yns_yellow">
                Delete <span class="fas fa-trash ml-1"></span>
              </a>
            </div>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-x-4 font-heading text-lg">
          <div class="col px-8 pt-8">
            <div class="group border-b border-white">
              <div class="group mb-4 text-center">
                <p class="flex flex-col text-2xl font-bold">Headliner<a
                    href="{{ route('singleService', ['serviceName' => 'Band', 'serviceId' => $headliner->id]) }}"
                    class="font-normal transition duration-150 ease-in-out hover:text-yns_yellow">{{ $headliner->name ?? 'No Headliner' }}</a>
                </p>
              </div>
              <div class="group mb-4 text-center">
                <p class="flex flex-col text-xl font-bold">Main Support<a
                    href="{{ route('singleService', ['serviceName' => 'Band', 'serviceId' => $mainSupport->id]) }}"
                    class="font-normal transition duration-150 ease-in-out hover:text-yns_yellow">{{ $mainSupport->name ?? 'No Main Support' }}</a>
                </p>
              </div>
              <div class="group mb-4 text-center">
                @if (count($otherBands) > 0)
                  @foreach ($otherBands as $band)
                    <p class="flex flex-col text-lg font-bold">Band<a
                        href="{{ route('singleService', ['serviceName' => 'Band', 'serviceId' => $band->id]) }}"
                        class="font-normal transition duration-150 ease-in-out hover:text-yns_yellow">{{ $band->name }}</a>
                    </p>
                  @endforeach
                @endif
              </div>
              <div class="group mb-4 text-center">
                <p class="text-md flex flex-col font-bold">Opener<a
                    href="{{ route('singleService', ['serviceName' => 'Band', 'serviceId' => $opener->id]) }}"
                    class="font-normal transition duration-150 ease-in-out hover:text-yns_yellow">{{ $opener->name ?? 'No Opener' }}</a>
                </p>
              </div>
            </div>
            <div class="group my-2 flex flex-row justify-between">
              <p class="font-bold">Start Time:</p>
              <p class="font-bold">End Time:</p>
            </div>
            <div class="group mb-2 text-center">
              <p class="font-bold">Location:
                @forelse($event->venues as $venue)
                  <a class="transition duration-150 ease-in-out hover:text-yns_yellow"
                    href="{{ route('venues', $venue->id) }}">{{ $venue->location }}</a>
                @empty
                  No Venue Assigned
                @endforelse
              </p>
            </div>
            <div class="group mb-2 text-center">
              <p class="font-bold">Promoter:
                @forelse($event->promoters as $promoter)
                  <a class="transition duration-150 ease-in-out hover:text-yns_yellow"
                    href="{{ route('promoters', $promoter->id) }}">{{ $promoter->name }}</a>
                @empty
                  No Promoter Assigned
                @endforelse
              </p>
            </div>

            <div class="group mb-2 flex flex-row justify-between text-center">
              {{-- <p class="font-bold">Pre Sale Tickets:</p> --}}
              <p class="font-bold">On The Door Tickets:</p>
            </div>
          </div>
          <div class="col relative place-content-center">
            <div
              class="absolute right-2 top-12 flex h-12 w-12 place-items-center justify-center rounded-50 bg-opac_8_black p-2 transition duration-150 ease-in-out hover:bg-opac_5_black">
              <span class="fas fa-search-plus"></span>
            </div>
            <img src="{{ asset($event->poster_url) }}" alt="{{ $event->name }} Poster"
              class="cursor-pointer object-cover transition duration-150 ease-in-out hover:opacity-75" id="eventPoster"
              onclick="openModal()">
            <div id="modal"
              class="fixed inset-0 flex hidden scale-95 transform justify-center duration-300 ease-in-out">
              <div class="rounded-lg bg-white p-4">
                <span
                  class="absolute right-2 top-2 cursor-pointer transition duration-150 ease-in-out hover:text-yns_yellow"
                  onclick="closeModal()"><span class="fas fa-times"></span></span>
                <img src="{{ asset($event->poster_url) }}" alt="Enlarged Event Poster" class="max-h-80 max-w-3xl" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</x-app-layout>
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
