<x-app-layout :dashboardType="$dashboardType" :modules="$modules">
  <x-slot name="header">
    <x-sub-nav :userId="$userId" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg border border-white bg-yns_dark_gray text-white">
        <div class="header border-b border-b-white px-8 py-8">
          <h1 class="mb-8 font-heading text-4xl font-bold">Edit Document</h1>

          @if (session('success'))
            <div class="mb-4 text-green-500">{{ session('success') }}</div>
          @endif

          @if ($errors->any())
            <div class="mb-4 text-red-500">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form
            action="{{ route('admin.dashboard.document.update', ['dashboardType' => $dashboardType, 'id' => $document->id]) }}"
            method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4 grid grid-cols-1 gap-x-8 gap-y-4">
              <div class="group">
                <x-input-label-dark>Document Title</x-input-label-dark>
                <x-text-input id="title" name="title" value="{{ old('title', $document->title) }}"
                  required></x-text-input>
                @error('title')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
              </div>

              <div class="group">
                <x-input-label-dark>Description</x-input-label-dark>
                <x-textarea-input class="w-full" id="description"
                  name="description">{{ old('description', $document->description) }}</x-textarea-input>
                @error('description')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
              </div>

              <div class="group">
                <x-input-label-dark>Tags</x-input-label-dark>
                <div class="mb-4">
                  @php
                    $categories = json_decode($document->category, true) ?? [];
                    // If categories are not arrays, just wrap them in an array
                    if (!is_array($categories)) {
                        $categories = [$categories];
                    }
                  @endphp

                  <x-multi-select id="tags" name="tags[]" :options="[
                      'EPK' => 'EPK',
                      'Rider Instructions' => 'Rider Instructions',
                      'Tech Spec' => 'Tech Spec',
                      'Artwork' => 'Artwork',
                      'Band Picture' => 'Band Picture',
                      'Setlist' => 'Setlist',
                  ]" :selected="old('tags', $categories)" />
                </div>
                @error('tags')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
              </div>



              <input type="hidden" id="uploaded_file_path" name="uploaded_file_path"
                value="{{ $document->file_path }}">

              <button type="submit"
                class="mt-8 flex w-full justify-center rounded-lg border border-yns_cyan bg-yns_cyan px-4 py-2 font-heading text-xl text-black transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">Save
                Document</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>

<script>
  $(document).ready(function() {
    $('#tags').select2({
      placeholder: 'Select tags',
      allowClear: true,
      tags: true, // Allow users to add custom tags
    });

    const selectedTags = @json(old('tags', $categories));
    console.log('Selected tags:', selectedTags);

    $('#tags').val(selectedTags).trigger('change');
  });
</script>
