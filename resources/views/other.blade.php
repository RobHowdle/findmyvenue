<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">
      {{ __('Other') }}
    </h1>
  </x-slot>

  <div class="other-wrapper py-8">
    <div class="wrapper mx-auto grid px-8">

    </div>
  </div>
</x-guest-layout>
{{-- 
@push('scripts')
  {{ $dataTable->scripts() }} 
@endpush --}}
