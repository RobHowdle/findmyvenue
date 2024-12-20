<header>
  <h2 class="text-md font-heading font-medium text-white">
    {{ __('Designer Details') }}
  </h2>
</header>
<form method="POST" action="{{ route('designer.update', ['dashboardType' => $dashboardType, 'user' => $user]) }}"
  class="grid grid-cols-3 gap-x-8 gap-y-8" enctype="multipart/form-data">
  @csrf
  @method('PUT')
  <div class="col-start-1 col-end-2">
    <div class="group mb-6">
      <x-input-label-dark for="name">Venue Name</x-input-label-dark>
      <x-text-input id="name" name="name" value="{{ old('name', $name) }}"></x-text-input>
      @error('name')
        <p class="yns_red mt-1 text-sm">{{ $message }}</p>
      @enderror
    </div>

    <div class="group mb-6">
      <x-input-label-dark for="contact_name">Contact Name</x-input-label-dark>
      <x-text-input id="contact_name" name="contact_name"
        value="{{ old('contact_name', $contact_name) }}"></x-text-input>
      @error('contact_name')
        <p class="yns_red mt-1 text-sm">{{ $message }}</p>
      @enderror
    </div>

    <div class="group mb-6">
      <x-input-label-dark>Where are you based?</x-input-label-dark>
      <x-text-input id="address-input" name="address-input" value="{{ old('location', $designerUserData['location']) }}"
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
      <x-input-label-dark for="email">Email</x-input-label-dark>
      <x-text-input id="contact_email" name="contact_email"
        value="{{ old('contact_email', $designerUserData['contact_email']) }}"></x-text-input>
      @error('contact_email')
        <p class="yns_red mt-1 text-sm">{{ $message }}</p>
      @enderror
    </div>

    <div class="group mb-6">
      <x-input-label-dark for="contact_number">Contact Phone</x-input-label-dark>
      <x-text-input id="contact_number" name="contact_number"
        value="{{ old('contact_number', $designerUserData['contact_number']) }}"></x-text-input>
      @error('contact_number')
        <p class="yns_red mt-1 text-sm">{{ $message }}</p>
      @enderror
    </div>
  </div>

  @if (is_array($platformsToCheck))
    <div class="col-start-2 col-end-3">
      @foreach ($platformsToCheck as $platform)
        <div class="group mb-6">
          <x-input-label-dark for="{{ $platform }}">{{ ucfirst($platform) }}:</x-input-label-dark>

          @php
            $links =
                isset($platforms[$platform]) && is_array($platforms[$platform])
                    ? $platforms[$platform]
                    : ($platforms[$platform]
                        ? [$platforms[$platform]]
                        : []);
          @endphp

          @foreach ($links as $index => $link)
            <x-text-input id="{{ $platform }}-{{ $index }}" name="contact_links[{{ $platform }}][]"
              value="{{ old('contact_links.' . $platform . '.' . $index, $link) }}">
            </x-text-input>
          @endforeach

          @if (empty($links))
            <x-text-input id="{{ $platform }}-new" name="contact_links[{{ $platform }}][]"
              value="{{ old('contact_links.' . $platform . '.new', '') }}"
              placeholder="Add a {{ ucfirst($platform) }} link">
            </x-text-input>
          @endif

          @error('contact_links.' . $platform . '.*')
            <p class="yns_red mt-1 text-sm">{{ $message }}</p>
          @enderror
        </div>
      @endforeach
    </div>
  @endif

  <div class="col-start-3 col-end-4">
    <div class="group mb-6 flex flex-col items-center">
      <x-input-label-dark for="logo" class="text-left">Logo</x-input-label-dark>
      <x-input-file id="logo" name="logo" onchange="previewLogo(event)"></x-input-file>

      <img id="logo-preview" src="{{ $logo }}" alt="Logo Preview" class="mt-4 h-80 w-80 object-cover"
        style="display: {{ $logo ? 'block' : 'none' }};">

      @error('logo')
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
<script>
  function previewLogo(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('logo-preview');

    if (file) {
      const reader = new FileReader();

      reader.onload = function(e) {
        preview.src = e.target.result; // Set the preview to the file data
        preview.style.display = 'block'; // Show the preview image
        console.log('Preview URL:', e.target.result); // Log the preview URL
      };

      reader.readAsDataURL(file); // Read the file as a data URL
    }
  }
</script>
