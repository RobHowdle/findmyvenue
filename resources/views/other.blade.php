<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">
      {{ __('Other') }}
    </h1>
  </x-slot>

  <div class="other-wrapper py-8">
    <h1 class="text-center font-heading text-6xl text-white">Other Services</h1>
    <div class="wrapper mx-auto grid px-8">
      <div class="other-grid-wrapper mt-6 grid gap-8">
        @foreach ($otherServices as $other)
          <div class="service-block justify-self-center text-center text-white">
            @if ($other->otherServiceList->service_name == 'Photography')
              <a class="service-overlay"
                href="{{ route('singleServiceGroup', ['serviceName' => $other->otherServiceList->service_name, 'serviceId' => $other->id]) }}">
                <img src="{{ asset('storage/images/photography.jpg') }}">
                <p class="bg-black px-6 py-2 text-white">{{ $other->otherServiceList->service_name }}</p>
              </a>
            @elseif($other->otherServiceList->service_name == 'Videography')
              <div class="service-overlay">
                <img src="{{ asset('storage/images/videography.jpg') }}">
                <p class="bg-black px-6 py-2 text-white">{{ $other->otherServiceList->service_name }}</p>
              </div>
            @elseif($other->otherServiceList->service_name == 'Designer')
              <div class="service-overlay">
                <img src="{{ asset('storage/images/designer.jpg') }}">
                <p class="bg-black px-6 py-2 text-white">{{ $other->otherServiceList->service_name }}</p>
              </div>
            @elseif($other->otherServiceList->service_name == 'Band')
              <div class="service-overlay">
                <img src="{{ asset('storage/images/band.jpeg') }}">
                <p class="bg-black px-6 py-2 text-white">{{ $other->otherServiceList->service_name }}</p>
              </div>
            @endif
          </div>
        @endforeach
      </div>
    </div>
  </div>
</x-guest-layout>
