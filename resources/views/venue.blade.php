<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">
      {{ __('Promoters') }}
    </h1>
  </x-slot>

  <div class="venue-wrapper">
    {{ $venue->name }}
  </div>
</x-guest-layout>
