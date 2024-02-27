<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">Promoters
      {{ __('Promoters') }}
    </h1>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <h1 class="text-center font-heading text-6xl text-white">Locations</h1>
      {{-- {{ $dataTable->table() }} --}}
      <div class="relative mt-4 overflow-x-auto shadow-md sm:rounded-lg">
        <div class="locations-wrapper grid grid-cols-3">
          @foreach ($locations as $location)
            <div class="location-block">
              {{ $location['location'] }} - {{ $location['count'] }}
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</x-guest-layout>
{{-- 
@push('scripts')
  {{ $dataTable->scripts() }} 
@endpush --}}
