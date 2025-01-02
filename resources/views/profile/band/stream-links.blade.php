<header>
  <h2 class="text-md font-heading font-medium text-white">
    {{ __('Stream Urls') }}
  </h2>
</header>
<form method="POST" action="{{ route('band.update', ['dashboardType' => $dashboardType, 'user' => $user]) }}"
  class="grid grid-cols-3 gap-x-8 gap-y-8">
  @csrf
  @method('PUT')
  @if (is_array($streamPlatformsToCheck))
    <div class="col-start-1 col-end-2">
      @foreach ($streamPlatformsToCheck as $streamPlatform)
        <div class="group mb-6">
          <x-input-label-dark for="{{ $streamPlatform }}">{{ ucfirst($streamPlatform) }}:</x-input-label-dark>

          @php
            // Ensure the links for the platform are correctly handled as an array
            $links =
                isset($streamLinks[$streamPlatform]) && is_array($streamLinks[$streamPlatform])
                    ? $streamLinks[$streamPlatform]
                    : ($streamLinks[$streamPlatform]
                        ? [$streamLinks[$streamPlatform]]
                        : []);
          @endphp

          @foreach ($links as $index => $link)
            <x-text-input id="{{ $streamPlatform }}-{{ $index }}" name="stream_links[{{ $streamPlatform }}][]"
              value="{{ old('stream_links.' . $streamPlatform . '.' . $index, $link) }}">
            </x-text-input>
          @endforeach

          <!-- If no links exist, provide a way to add one -->
          @if (empty($links))
            <x-text-input id="{{ $streamPlatform }}-new" name="contact_links[{{ $streamPlatform }}][]"
              value="{{ old('stream_links.' . $streamPlatform . '.new', '') }}"
              placeholder="Add a {{ ucfirst($streamPlatform) }} link">
            </x-text-input>
          @endif

          @error('stream_links.' . $streamPlatform . '.*')
            <p class="yns_red mt-1 text-sm">{{ $message }}</p>
          @enderror
        </div>
      @endforeach
      <div class="flex items-center gap-4">
        <button type="submit"
          class="mt-8 rounded-lg border border-white bg-white px-4 py-2 font-heading font-bold text-black transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">Save</button>
        @if (session('status') === 'profile-updated')
          <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
            class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
        @endif
      </div>
    </div>
  @endif
</form>
