<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
      {{ __('Profile') }}
    </h2>
  </x-slot>

  <div x-data="{ selected: 1, open: false, selectedTab: 1 }" class="grid min-h-screen grid-cols-[1fr,2fr] gap-4">
    <div class="h-full bg-opac_8_black">
      <div class="py-8 font-heading text-xl">
        <p class="mb-8 px-8 font-bold">Settings</p>
        <div class="flex flex-col items-start gap-4">
          <button @click="selected = 1; selectedTab = 1"
            :class="{ 'bg-gradient-button': selected === 1, 'bg-yns_dark_gray': selected !== 1 }"
            class="group relative w-full px-8 py-2 text-left text-white transition duration-150 ease-in-out">
            <span class="absolute inset-0 transition-opacity duration-300 ease-in-out"
              :class="{ 'opacity-100': selected === 1, 'opacity-0': selected !== 1 }"></span>
            <span class="relative z-10">User</span>
          </button>

          <div class="w-full">
            <button @click="open = !open"
              class="group relative w-full bg-yns_dark_gray px-8 py-2 text-left text-white transition duration-150 ease-in-out">
              <span
                class="absolute inset-0 bg-gradient-button opacity-0 transition-opacity duration-300 ease-in-out group-hover:opacity-100"></span>
              <span class="relative z-10 flex items-center justify-between">
                <span>Public Profile</span>
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200"
                  fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200"
                  fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
              </span> </button>

            <div x-show="open" x-transition class="mt-4">
              <div class="flex flex-col items-start gap-4">
                <button @click="selected = 2; selectedTab = 2"
                  :class="{ 'bg-gradient-button': selected === 2, 'bg-yns_dark_gray': selected !== 2 }"
                  class="group relative w-full px-8 py-2 text-left text-white transition duration-150 ease-in-out">
                  <span class="absolute inset-0 transition-opacity duration-300 ease-in-out"
                    :class="{ 'opacity-100': selected === 2, 'opacity-0': selected !== 2 }"></span>
                  <span class="relative z-10">Basic Information</span>
                </button>
                <button @click="selected = 3; selectedTab = 3"
                  :class="{ 'bg-gradient-button': selected === 3, 'bg-yns_dark_gray': selected !== 3 }"
                  class="group relative w-full px-8 py-2 text-left text-white transition duration-150 ease-in-out">
                  <span class="absolute inset-0 transition-opacity duration-300 ease-in-out"
                    :class="{ 'opacity-100': selected === 3, 'opacity-0': selected !== 3 }"></span>
                  <span class="relative z-10">About</span>
                </button>
                <button @click="selected = 4; selectedTab = 4"
                  :class="{ 'bg-gradient-button': selected === 4, 'bg-yns_dark_gray': selected !== 4 }"
                  class="group relative w-full px-8 py-2 text-left text-white transition duration-150 ease-in-out">
                  <span class="absolute inset-0 transition-opacity duration-300 ease-in-out"
                    :class="{ 'opacity-100': selected === 4, 'opacity-0': selected !== 4 }"></span>
                  <span class="relative z-10">My Venues</span>
                </button>
                <button @click="selected = 5; selectedTab = 5"
                  :class="{ 'bg-gradient-button': selected === 5, 'bg-yns_dark_gray': selected !== 5 }"
                  class="group relative w-full px-8 py-2 text-left text-white transition duration-150 ease-in-out">
                  <span class="absolute inset-0 transition-opacity duration-300 ease-in-out"
                    :class="{ 'opacity-100': selected === 5, 'opacity-0': selected !== 5 }"></span>
                  <span class="relative z-10">My Events</span>
                </button>
                <button @click="selected = 6; selectedTab = 6"
                  :class="{ 'bg-gradient-button': selected === 6, 'bg-yns_dark_gray': selected !== 6 }"
                  class="group relative w-full px-8 py-2 text-left text-white transition duration-150 ease-in-out">
                  <span class="absolute inset-0 transition-opacity duration-300 ease-in-out"
                    :class="{ 'opacity-100': selected === 6, 'opacity-0': selected !== 6 }"></span>
                  <span class="relative z-10">My Bands</span>
                </button>
                <button @click="selected = 7; selectedTab = 7"
                  :class="{ 'bg-gradient-button': selected === 7, 'bg-yns_dark_gray': selected !== 7 }"
                  class="group relative w-full px-8 py-2 text-left text-white transition duration-150 ease-in-out">
                  <span class="absolute inset-0 transition-opacity duration-300 ease-in-out"
                    :class="{ 'opacity-100': selected === 7, 'opacity-0': selected !== 7 }"></span>
                  <span class="relative z-10">Genres</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="mx-4 my-4 h-auto self-center font-heading">
      <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
        <div x-show="selectedTab === 1" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
          <div class="max-w-xl">
            <p class="text-xl font-bold">User Settings</p>
            @include('profile.partials.edit-user-details', [
                'userRole' => $userRole,
                'name' => $name,
            ])
          </div>
        </div>
        <div x-show="selectedTab === 2" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
          <div class="w-full">
            @include('profile.partials.basic-information-form', [
                'name' => $name,
                'promoterName' => $promoterName,
            ]) </div>
        </div>
        <div x-show="selectedTab === 3" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
          <div class="w-full">
            @include('profile.partials.about-information', [
                'about' => $about,
            ]) </div>
        </div>
        <div x-show="selectedTab === 4" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
          <div class="w-full">
            @include('profile.partials.my-venues-information', [
                'myVenues' => $myVenues,
            ]) </div>
        </div>
        <div x-show="selectedTab === 5" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
          <div class="w-full">
            5
            @include('profile.partials.edit-user-details', [
                'userRole' => $userRole,
                'name' => $name,
            ]) </div>
        </div>
        <div x-show="selectedTab === 6" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
          <div class="w-full">
            6
            @include('profile.partials.edit-user-details', [
                'userRole' => $userRole,
                'name' => $name,
            ]) </div>
        </div>
        <div x-show="selectedTab === 7" class="bg-opac_8_black p-4 shadow sm:rounded-lg sm:p-8" x-cloak>
          <div class="w-full">
            7
            @include('profile.partials.edit-user-details', [
                'userRole' => $userRole,
                'name' => $name,
            ]) </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>

<style>
  [x-cloak] {
    display: none;
  }
</style>
