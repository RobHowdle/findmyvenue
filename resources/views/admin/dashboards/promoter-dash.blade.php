<x-app-layout>
  <x-slot name="header">
    <x-promoter-sub-nav :promoterId="$promoter->id" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg bg-ynsDarkGray px-16 py-12 text-center text-white">
        <x-greeting />
        <p class="mb-12 font-heading text-xl">This week you have:</p>
        <div class="grid grid-cols-4 items-center">
          <a href="#"
            class="flex flex-col items-center text-center transition duration-150 ease-in-out hover:text-ynsYellow">
            <span class="fas fa-calendar-alt mb-4 h-14 w-14"></span>
            3 Events
          </a>
          <a href="#"
            class="flex flex-col items-center text-center transition duration-150 ease-in-out hover:text-ynsYellow">
            <span class="fas fa-guitar mb-4 h-14 w-14"></span>
            6 Aviliable Bands
          </a>
          <a href="#"
            class="flex flex-col items-center text-center transition duration-150 ease-in-out hover:text-ynsYellow">
            <span class="fas fa-star mb-4 h-14 w-14"></span>
            {{ $pendingReviews }} Pending Review{{ $pendingReviews > 1 ? 's' : '' }}
          </a>
          <a href="#"
            class="flex flex-col items-center text-center transition duration-150 ease-in-out hover:text-ynsYellow">
            <span class="fas fa-list mb-4 h-14 w-14"></span>
            10 Todo Items
          </a>
        </div>
      </div>
    </div>

    <div class="relative shadow-md sm:rounded-lg">
      <div class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg bg-ynsDarkGray px-16 py-12 text-center text-white">
        <p class="mb-8 font-heading text-xl font-bold">Quick Links</p>
        <div class="grid grid-cols-5 items-center gap-y-12">
          <a href="#"
            class="group flex flex-col items-center text-center transition duration-150 ease-in-out hover:text-ynsYellow">
            <span
              class="fas fa-user mb-4 h-14 w-14 rounded-lg bg-white px-1 py-1 text-black transition duration-150 ease-in-out group-hover:text-ynsYellow"></span>
            New User
          </a>
          <a href="#"
            class="group flex flex-col items-center text-center transition duration-150 ease-in-out hover:text-ynsYellow">
            <span
              class="fas fa-calendar-alt mb-4 h-14 w-14 rounded-lg bg-white px-1 py-1 text-black transition duration-150 ease-in-out group-hover:text-ynsYellow"></span>
            New Event
          </a>
          <a href="{{ route('promoter.dashboard.finances.new') }}"
            class="group flex flex-col items-center text-center transition duration-150 ease-in-out hover:text-ynsYellow">
            <span
              class="fas fa-pound-sign mb-4 h-14 w-14 rounded-lg bg-white px-1 py-1 text-black transition duration-150 ease-in-out group-hover:text-ynsYellow"></span>
            New Budget
          </a>
          <a href="#"
            class="group flex flex-col items-center text-center transition duration-150 ease-in-out hover:text-ynsYellow">
            <span
              class="fas fa-sticky-note mb-4 h-14 w-14 rounded-lg bg-white px-1 py-1 text-black transition duration-150 ease-in-out group-hover:text-ynsYellow"></span>
            New Note
          </a>
          <a href="#"
            class="group flex flex-col items-center text-center transition duration-150 ease-in-out hover:text-ynsYellow">
            <span
              class="fas fa-list mb-4 h-14 w-14 rounded-lg bg-white px-1 py-1 text-black transition duration-150 ease-in-out group-hover:text-ynsYellow"></span>
            New Todo Item
          </a>

          <a href="#"
            class="group flex flex-col items-center text-center transition duration-150 ease-in-out hover:text-ynsYellow">
            <span
              class="fas fa-user mb-4 h-14 w-14 rounded-lg bg-white px-1 py-1 text-black transition duration-150 ease-in-out group-hover:text-ynsYellow"></span>
            Users
          </a>
          <a href="#"
            class="group flex flex-col items-center text-center transition duration-150 ease-in-out hover:text-ynsYellow">
            <span
              class="fas fa-calendar-alt mb-4 h-14 w-14 rounded-lg bg-white px-1 py-1 text-black transition duration-150 ease-in-out group-hover:text-ynsYellow"></span>
            Events
          </a>
          <a href="{{ route('promoter.dashboard.finances') }}"
            class="group flex flex-col items-center text-center transition duration-150 ease-in-out hover:text-ynsYellow">
            <span
              class="fas fa-pound-sign mb-4 h-14 w-14 rounded-lg bg-white px-1 py-1 text-black transition duration-150 ease-in-out group-hover:text-ynsYellow"></span>
            Budgets
          </a>
          <a href="#"
            class="group flex flex-col items-center text-center transition duration-150 ease-in-out hover:text-ynsYellow">
            <span
              class="fas fa-sticky-note mb-4 h-14 w-14 rounded-lg bg-white px-1 py-1 text-black transition duration-150 ease-in-out group-hover:text-ynsYellow"></span>
            Notes
          </a>
          <a href="#"
            class="group flex flex-col items-center text-center transition duration-150 ease-in-out hover:text-ynsYellow">
            <span
              class="fas fa-list mb-4 h-14 w-14 rounded-lg bg-white px-1 py-1 text-black transition duration-150 ease-in-out group-hover:text-ynsYellow"></span>
            Todo List
          </a>
        </div>
      </div>
    </div>

  </div>
</x-app-layout>
