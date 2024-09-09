<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">
      {{ __('Other') }}
    </h1>
  </x-slot>

  <div class="mx-auto h-screen w-full max-w-screen-2xl place-content-center">
    <h1 class="mb-8 text-center font-heading text-6xl text-white">Other Services</h1>
    <div class="relative shadow-md sm:rounded-lg">
      <div class="mx-auto grid grid-cols-4 px-8">
        @forelse ($otherServices as $other)
          <div class="service-block justify-self-center text-center">
            <a class="service-overlay"
              href="{{ route('singleServiceGroup', ['serviceName' => $other->otherServiceList->service_name]) }}">
              <img src={{ $other->otherServiceList->image_url }}>
              <p class="bg-black px-6 py-2 text-white">
                {{ $other->otherServiceList->service_name }} (
                {{ $serviceCounts[$other->other_service_id] ?? 0 }})</p>
            </a>
          </div>
        @empty
          <div class="col-start-1 col-end-5 bg-opac8Black p-20 text-center text-2xl text-white">
            <p>Sorry! We don't have anything here yet. Come back later and try again.</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>
</x-guest-layout>
