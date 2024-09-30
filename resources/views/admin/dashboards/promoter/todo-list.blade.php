<x-app-layout>
  <x-slot name="header">
    <x-promoter-sub-nav :promoter="$promoter" :promoterId="$promoter->id" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg bg-ynsDarkGray text-white">
        <div class="rounded-lg border border-white px-8 py-4">
          <p class="mb-4 font-heading text-4xl font-bold">Todo List</p>
          <div class="flex flex-row items-center gap-8 border-b border-b-white pb-4">
            <div class="group">
              <x-input-label>Item</x-input-label>
              <x-textarea-input class="mt-2 h-32 w-96"></x-textarea-input>
            </div>
            <button
              class="mt-8 h-10 w-40 rounded-lg border border-white bg-white px-4 py-2 font-heading font-bold text-black transition duration-150 ease-in-out hover:border-ynsYellow hover:text-ynsYellow">Add</button>
          </div>
          <div class="grid grid-cols-3 gap-x-4 gap-y-6 pt-6">
            @foreach ($todoItems as $item)
              <div class="min-h-52 mx-auto w-full max-w-xs rounded-lg bg-ynsDarkBlue text-white">
                <div class="flex h-full flex-col justify-between rounded-lg border border-ynsRed px-4 py-4">
                  <!-- Adjusted padding -->
                  <p>{{ $item->item }}</p>
                  <div class="mt-4 flex flex-row gap-4"> <!-- Adjusted gap -->
                    <button
                      class="rounded-lg border border-white bg-ynsDarkGray px-4 py-2 text-white transition duration-150 ease-in-out hover:border-ynsRed hover:text-ynsRed">Delete</button>
                    <button
                      class="rounded-lg border border-white bg-ynsDarkGray px-4 py-2 text-white transition duration-150 ease-in-out hover:border-ynsYellow hover:text-ynsYellow">Complete</button>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
          <div class="mt-4 flex flex-row gap-4">
            <button
              class="h-10 w-40 rounded-lg border border-white bg-white px-4 py-2 font-heading font-bold text-black transition duration-150 ease-in-out hover:border-ynsYellow hover:text-ynsYellow">Load
              More</button>
            <button
              class="h-10 w-40 rounded-lg border border-white bg-white px-4 py-2 font-heading font-bold text-black transition duration-150 ease-in-out hover:border-ynsYellow hover:text-ynsYellow">View
              Completed</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
