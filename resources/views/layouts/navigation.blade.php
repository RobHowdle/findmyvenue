<nav x-data="{ open: false }" class="border-b border-gray-100 bg-white dark:border-gray-700 dark:bg-gray-800">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 justify-between">
      <div class="flex">
        <div class="flex shrink-0 items-center">
          <a href="/">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
          </a>
        </div>

        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
          <a href="{{ route('dashboard.index') }}"
            class="inline-flex items-center border-b-2 font-heading text-sm text-white hover:border-b-yns_yellow hover:text-yns_yellow">Dashboard</a>

          @php
            $links = [
                'manage_venue' => [
                    'finances' => route('promoter.dashboard.finances', ['dashboardType' => $dashboardType]),
                    'events' => route('admin.dashboard.show-events', ['dashboardType' => $dashboardType]),
                    'todo_list' => route('admin.dashboard.todo-list', ['dashboardType' => $dashboardType]),
                    'reviews' => route('admin.dashboard.get-reviews', [
                        'filter' => 'all',
                        'dashboardType' => $dashboardType,
                    ]),
                    'notes' => route('admin.dashboard.show-notes', ['dashboardType' => $dashboardType]),
                ],
                'manage_promoter' => [
                    'finances' => route('promoter.dashboard.finances', ['dashboardType' => 'promoter']),
                    'events' => route('admin.dashboard.show-events', ['dashboardType' => 'promoter']),
                    'todo_list' => route('admin.dashboard.todo-list', ['dashboardType' => 'promoter']),
                    'reviews' => route('admin.dashboard.get-reviews', [
                        'filter' => 'all',
                        'dashboardType' => 'promoter',
                    ]),
                    'notes' => route('admin.dashboard.show-notes', ['dashboardType' => 'promoter']),
                ],
                'manage_band' => [
                    'documents' => route('admin.dashboard.documents.index', ['dashboardType' => 'band']),
                    'events' => route('admin.dashboard.show-events', ['dashboardType' => 'band']),
                    'todo_list' => route('admin.dashboard.todo-list', ['dashboardType' => 'band']),
                    'reviews' => route('admin.dashboard.get-reviews', ['filter' => 'all', 'dashboardType' => 'band']),
                    'notes' => route('admin.dashboard.show-notes', ['dashboardType' => 'band']),
                ],
            ];
          @endphp


          @foreach ($links as $permission => $subLinks)
            @can($permission)
              @foreach ($subLinks as $module => $url)
                @if (isset($modules[$module]))
                  @php
                    $isEnabled = $modules[$module]['is_enabled'];
                  @endphp

                  @if ($isEnabled)
                    <a href="{{ $url }}"
                      class="{{ request()->is('dashboard/*/' . $module . '*') ? 'border-b-yns_yellow' : '' }} inline-flex items-center border-b-2 font-heading text-sm text-white transition duration-150 ease-in-out hover:border-b-yns_yellow hover:text-yns_yellow">
                      {{ ucfirst($module) }}
                    </a>
                  @endif
                @endif
              @endforeach
            @endcan
          @endforeach

          @can(['manage_band', 'manage_photographer', 'manage_videographer', 'manage_designer'])
            @if ($modules->contains('module_name', 'other') && $modules->where('module_name', 'other')->first()->is_enabled)
              <x-nav-link :href="route('admin.createOther')" :active="request()->routeIs('createOther')">{{ __('Other') }}</x-nav-link>
            @endif
          @endcan
        </div>
      </div>

      <div class="hidden sm:ms-6 sm:flex sm:items-center">
        <x-dropdown class="bg-opac_8_black" align="right" width="48">
          <x-slot name="trigger">
            <button
              class="inline-flex items-center rounded-md border border-transparent px-3 py-2 font-heading font-medium text-white transition duration-150 ease-in-out focus:outline-none">
              <div>{{ Auth::user()->first_name }}</div>
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
            <x-dropdown-link :href="route('profile.edit', ['dashboardType' => $dashboardType, 'id' => Auth::user()->id])">
              {{ __('Profile') }}
            </x-dropdown-link>
            <x-dropdown-link :href="route('logout')"
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              {{ __('Logout') }}
            </x-dropdown-link>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
              @csrf
            </form>
          </x-slot>
        </x-dropdown>
      </div>
    </div>
  </div>
</nav>
