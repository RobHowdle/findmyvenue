<x-app-layout :dashboardType="$dashboardType" :modules="$modules">
  <x-slot name="header">
    <x-sub-nav :userId="$userId" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg border border-white bg-yns_dark_gray text-white">
        <div class="header border-b border-b-white px-8 py-8">
          <div class="flex flex-row items-center justify-between">
            <h1 class="mb-8 font-heading text-4xl font-bold">{{ $document->title }}</h1>
            <x-button
              href="{{ route('admin.dashboard.document.edit', ['dashboardType' => $dashboardType, 'id' => $document->id]) }}"
              id="edit-document-btn" label="Edit Document" />
          </div>

          <div class="group mb-4">
            <x-input-label-dark>Description</x-input-label-dark>
            <p class="text-lg">{{ $document->description }}</p>
          </div>

          <div class="group mb-4">
            <x-input-label-dark>Tags</x-input-label-dark>
            <ul class="list-disc pl-5">
              @php
                $categories = json_decode($document->category, true);
              @endphp

              @if (is_array($categories))
                @foreach ($categories as $categoryArray)
                  @if (is_array($categoryArray))
                    @foreach ($categoryArray as $category)
                      <li class="text-lg">{{ $category }}</li>
                    @endforeach
                  @else
                    <li class="text-lg">{{ $categoryArray }}</li>
                  @endif
                @endforeach
              @else
                <li class="text-lg">No tags available</li>
              @endif
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
