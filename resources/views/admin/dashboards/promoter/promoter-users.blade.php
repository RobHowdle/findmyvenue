<x-app-layout>
  <x-slot name="header">
    <x-promoter-sub-nav :promoter="$promoter" :promoterId="$promoter->id" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <p class="text-center font-heading text-4xl font-bold">Users</p>
    <div class="relative shadow-md sm:rounded-lg">
      <div class="relative z-0 overflow-x-auto">
        <table class="w-full border border-white text-left font-sans rtl:text-right" id="otherServices">
          <thead class="text-white underline dark:bg-black">
            <tr>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">
                Name
              </th>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">
                Email
              </th>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">
                Role
              </th>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">
                Date Added
              </th>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">
                Actions
              </th>
            </tr>
          </thead>
          <tbody>
            @if ($users)
              @foreach ($users as $user)
                <tr class="odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-black even:dark:bg-gray-900">
                  <th scope="row"
                    class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
                    {{ $user->name }}
                  </th>
                  <td class="rating-wrapper flex whitespace-nowrap sm:py-3 sm:text-base md:py-2 lg:py-4">
                    {{ $user->email }}
                  </td>
                  <td
                    class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
                    //
                  </td>
                  <td
                    class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
                    //
                  </td>

                  <td
                    class="flex gap-4 whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
                    {{-- @if (auth()->user()->id == $promoter->main_contact_id) --}}
                    {{-- <form action="{{ route('promoter.removeUser', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure?')">Remove</button>
                        </form> --}}
                    {{-- @endif --}}
                    <button type="submit" onclick="return confirm('Are you sure?')">Remove</button>
                  </td>
                </tr>
              @endforeach
            @else
              <td>No Users</td>
            @endif
          </tbody>
        </table>
      </div>
    </div>
</x-app-layout>

<script></script>
