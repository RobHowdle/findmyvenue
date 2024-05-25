<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">
      {{ __('Other') }} - Photography
    </h1>
  </x-slot>

  <div class="other-wrapper py-8">
    <h1 class="text-center font-heading text-6xl text-white">{{ $otherTitle->service_name }}</h1>
    <div class="wrapper mx-auto grid px-8">
      <div class="other-grid-wrapper mt-6 grid grid-cols-4 gap-8">
        @foreach ($otherService as $other)
          <div class="service-block justify-self-center text-center text-white">
            <a class="service-overlay"
              href="{{ route('singleService', ['serviceName' => $other->otherServiceList->service_name, 'serviceId' => $other->id]) }}">
              <img src="{{ asset($other->logo_url) }}" alt="{{ $other->name }} Logo">
              <p class="bg-black px-6 py-2 text-white">{{ $other->name }}</p>
            </a>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</x-guest-layout>
