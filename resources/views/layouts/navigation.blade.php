<nav x-data="{ open: false }" class="border-b border-gray-100 bg-white dark:border-gray-700 dark:bg-gray-800">
  <!-- Primary Navigation Menu -->
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 justify-between">
      <div class="flex">
        <!-- Logo -->
        <div class="flex shrink-0 items-center">
          <a href="/">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
          </a>
        </div>

        <!-- Navigation Links -->
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
          <a href="{{ route('dashboard') }}"
            class="{{ request()->is('dashboard/promoter') ? ' border-b-yns_yellow' : '' }} inline-flex items-center border-b-2 font-heading text-sm text-white transition duration-150 ease-in-out hover:border-b-yns_yellow hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-white dark:hover:text-yns_yellow">Dashboard</a>

          @can('manage_venue')
            <x-nav-link :href="route('admin.venues')" :active="request()->routeIs('venues')">
              {{ __('Venues') }}
            </x-nav-link>
          @endcan

          @can('manage_promoter')
            <a href="{{ route('promoter.dashboard.finances') }}"
              class="{{ request()->is('dashboard/promoter/finances*') ? ' border-b-yns_yellow' : '' }} inline-flex items-center border-b-2 font-heading text-sm text-white transition duration-150 ease-in-out hover:border-b-yns_yellow hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-white dark:hover:text-yns_yellow">Finances</a>
            <a href="{{ route('admin.dashboard.promoter.show-events') }}"
              class="{{ request()->is('dashboard/promoter/events*') ? ' border-b-yns_yellow' : '' }} inline-flex items-center border-b-2 font-heading text-sm text-white transition duration-150 ease-in-out hover:border-b-yns_yellow hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-white dark:hover:text-yns_yellow">Events</a>
            <a href="{{ route('promoter.dashboard.todo-list') }}"
              class="{{ request()->is('dashboard/promoter/todo-list*') ? ' border-b-yns_yellow' : '' }} inline-flex items-center border-b-2 font-heading text-sm text-white transition duration-150 ease-in-out hover:border-b-yns_yellow hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-white dark:hover:text-yns_yellow">Todo
              List</a>
            {{-- <a href="{{ route('promoter.dashboard.reviews') }}"
              class="{{ request()->is('dashboard/promoter/reviews*') ? ' border-b-yns_yellow' : '' }} hover:border-b-yns_yellow dark:hover:text-yns_yellow inline-flex items-center border-b-2 font-heading text-sm text-white transition duration-150 ease-in-out hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-white">Finances</a>
            <a href="{{ route('promoter.dashboard.notes') }}"
              class="{{ request()->is('dashboard/promoter/notes*') ? ' border-b-yns_yellow' : '' }} hover:border-b-yns_yellow dark:hover:text-yns_yellow inline-flex items-center border-b-2 font-heading text-sm text-white transition duration-150 ease-in-out hover:text-gray-900 focus:rounded-sm focus:outline focus:outline-2 focus:outline-red-500 dark:text-white">Finances</a> --}}
          @endcan

          @can(['manage_band', 'manage_photographer', 'manage_designer'])
            <x-nav-link :href="route('admin.createOther')" :active="request()->routeIs('createOther')">
              {{ __('Other') }}
            </x-nav-link>
          @endcan
        </div>
      </div>

      <!-- Settings Dropdown -->
      <div class="hidden sm:ms-6 sm:flex sm:items-center">
        <x-dropdown class="bg-opac_8_black" align="right" width="48">
          <x-slot name="trigger">
            <button
              class="inline-flex items-center rounded-md border border-transparent px-3 py-2 font-heading font-medium text-white transition duration-150 ease-in-out focus:outline-none">
              <div>{{ Auth::user()->name }}</div>

              <div class="ms-1">
                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
                </svg>
              </div>
            </button>
          </x-slot>

          <x-slot name="content">
            <x-dropdown-link :href="route('profile.edit', Auth::user()->id)">
              {{ __('Profile') }}
            </x-dropdown-link>

            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
              @csrf

              <x-dropdown-link :href="route('logout')"
                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                {{ __('Log Out') }}
              </x-dropdown-link>
            </form>
          </x-slot>
        </x-dropdown>
      </div>

      <!-- Hamburger -->
      <div class="-me-2 flex items-center sm:hidden">
        <button @click="open = ! open"
          class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-900 dark:hover:text-gray-400 dark:focus:bg-gray-900 dark:focus:text-gray-400">
          <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round"
              stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
              stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Responsive Navigation Menu -->
  <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
    <div class="space-y-1 pb-3 pt-2">
      <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
      </x-responsive-nav-link>
    </div>

    <!-- Responsive Settings Options -->
    <div class="border-t border-gray-200 pb-1 pt-4 dark:border-gray-600">
      <div class="px-4">
        <div class="text-base font-medium text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
        <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
      </div>

      <div class="mt-3 space-y-1">
        <x-responsive-nav-link :href="route('profile.edit', Auth::user()->id)">
          {{ __('Profile') }}
        </x-responsive-nav-link>

        <!-- Authentication -->
        <form method="POST" action="{{ route('logout') }}">
          @csrf

          <x-responsive-nav-link :href="route('logout')"
            onclick="event.preventDefault();
                                        this.closest('form').submit();">
            {{ __('Log Out') }}
          </x-responsive-nav-link>
        </form>
      </div>
    </div>
  </div>
</nav>
