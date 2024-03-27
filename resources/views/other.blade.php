<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">
      {{ __('Other') }}
    </h1>
  </x-slot>

  <div class="other-wrapper py-8">
    <div class="wrapper mx-auto grid px-8">
      <h1 class="text-center font-heading text-6xl text-white">Other Services</h1>
      <div class="other-grid-wrapper mt-6 grid">
        @foreach ($otherServices as $other)
          <div class="service-block justify-self-center text-center text-white">
            @if ($other->services == 'Photography')
              <div class="service-overlay">
                <img src="{{ asset('storage/images/photography.jpg') }}">
                <p class="bg-black px-6 py-2 text-white">{{ $other->services }}</p>
              </div>
            @elseif($other->services == 'Videography')
              <div class="service-overlay">
                <img src="{{ asset('storage/images/videography.jpg') }}">
                <p class="bg-black px-6 py-2 text-white">{{ $other->services }}</p>
              </div>
            @elseif($other->services == 'Graphics Design')
              <div class="service-overlay">
                <img src="{{ asset('storage/images/designer.jpg') }}">
                <p class="bg-black px-6 py-2 text-white">{{ $other->services }}</p>
              </div>
            @elseif($other->services == 'Band')
              <div class="service-overlay">
                <img src="{{ asset('storage/images/band.jpeg') }}">
                <p class="bg-black px-6 py-2 text-white">{{ $other->services }}</p>
              </div>
            @endif
          </div>
        @endforeach
      </div>
    </div>
  </div>
</x-guest-layout>
{{-- 
@push('scripts')
  {{ $dataTable->scripts() }} 
@endpush --}}
