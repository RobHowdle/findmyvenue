<header>
  <h2 class="text-md font-heading font-medium text-white">
    {{ __('Promoter Details') }}
  </h2>
</header>
<form method="POST" action="{{ route('profile.update', ['user' => $user->id]) }}"
  class="grid grid-cols-3 gap-x-8 gap-y-8">
  @csrf
  @method('PUT')
  <div class="col-start-1 col-end-2">
    <div class="group mb-6">
      <x-input-label-dark for="promoterName">Promotions Company Name</x-input-label-dark>
      <x-text-input id="promoterName" name="promoterName" value="{{ old('promoterName', $promoterName) }}"></x-text-input>
      @error('promoterName')
        <p class="yns_red mt-1 text-sm">{{ $message }}</p>
      @enderror
    </div>

    <div class="group mb-6">
      <x-input-label-dark>Where are you based?</x-input-label-dark>
      <x-text-input id="address-input" name="address-input" value="{{ old('location', $location) }}"
        class="map-input"></x-text-input>
      @error('address-input')
        <p class="yns_red mt-1 text-sm">{{ $message }}</p>
      @enderror
    </div>

    <div id="address-map-container" style="width: 100%; height: 400px; display: none;">
      <div style="width: 100%; height: 100%;" id="address-map"></div>
    </div>

    <div class="group relative z-0 mb-5 hidden w-full">
      <input
        class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
        type="text" id="postal-town-input" name="postal-town-input" placeholder="Postal Town Input"
        value="{{ old('postal-town-input') }}">
      @error('postal-town-input')
        <p class="yns_red mt-1 text-sm">{{ $message }}</p>
      @enderror
      <input
        class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
        type="text" id="address-latitude" name="latitude" placeholder="Latitude" value="{{ old('latitude') }}">
      @error('latitude')
        <p class="yns_red mt-1 text-sm">{{ $message }}</p>
      @enderror
      <input
        class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
        type="text" id="address-longitude" name="longitude" placeholder="Longitude" value="{{ old('longitude') }}">
      @error('longitude')
        <p class="yns_red mt-1 text-sm">{{ $message }}</p>
      @enderror
    </div>

    <div class="group mb-6">
      <x-input-label-dark for="phone">Phone</x-input-label-dark>
      <x-text-input id="phone" name="phone" value="{{ old('phone', $phone) }}"></x-text-input>
      @error('phone')
        <p class="yns_red mt-1 text-sm">{{ $message }}</p>
      @enderror
    </div>

    <div class="group mb-6">
      <x-input-label-dark for="email">Email</x-input-label-dark>
      <x-text-input id="email" name="email" value="{{ old('email', $email) }}"></x-text-input>
      @error('email')
        <p class="yns_red mt-1 text-sm">{{ $message }}</p>
      @enderror
    </div>
  </div>

  <div class="col-start-2 col-end-3">
    @foreach ($platforms as $platform => $links)
      <div class="group mb-6">
        <x-input-label-dark for="{{ $platform }}">{{ ucfirst($platform) }}:</x-input-label-dark>

        {{-- Display all stored links --}}
        @foreach ($links as $index => $link)
          <x-text-input id="{{ $platform }}-{{ $index }}" name="contact_links[{{ $platform }}][]"
            value="{{ old('contact_links.' . $platform . '.' . $index, $link) }}">
          </x-text-input>
        @endforeach

        {{-- Always provide at least one empty field if there are no links --}}
        @if (empty($links))
          <x-text-input id="{{ $platform }}-new" name="contact_links[{{ $platform }}][]"
            value="{{ old('contact_links.' . $platform . '.new', '') }}"
            placeholder="Add a {{ ucfirst($platform) }} link">
          </x-text-input>
        @endif

        {{-- Optional: Display an error message for each platform --}}
        @error('contact_links.' . $platform . '.*')
          <p class="yns_red mt-1 text-sm">{{ $message }}</p>
        @enderror
      </div>
    @endforeach
  </div>


  <div class="col-start-3 col-end-4">
    <div class="group mb-6">
      <x-input-label-dark for="logo">Logo</x-input-label-dark>
      <img src="{{ asset($logo) }}" alt="{{ $promoterName }} Logo" class="_250img rounded-lg border border-white">
      <x-input-file id="logo" name="logo" class="mt-4"></x-input-file>
      @error('promoterName')
        <p class="yns_red mt-1 text-sm">{{ $message }}</p>
      @enderror
    </div>
  </div>

  <div class="flex items-center gap-4">
    <button type="submit"
      class="mt-8 rounded-lg border border-white bg-white px-4 py-2 font-heading font-bold text-black transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">Save</button>
    @if (session('status') === 'profile-updated')
      <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
        class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
    @endif
  </div>
</form>
