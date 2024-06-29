<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
      {{ __('Promoter List') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
        <div class="relative z-0 overflow-x-auto">
          <table class="w-full text-left font-sans rtl:text-right" id="promoters">
            <thead class="underline">
              <tr>
                <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">
                  Promoter
                </th>
                <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">
                  Location
                </th>
                <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody>
              @forelse ($promoters as $promoter)
                <tr class="even:bg-gray-800 odd:dark:bg-gray-600">
                  <th scope="row"
                    class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
                    {{ $promoter->name }}
                  </th>
                  <td
                    class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
                    {{ $promoter->postal_town }}
                  </td>
                  <td
                    class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
                    <a href="{{ route('admin.edit-promoter', $promoter->id) }}">Edit</a>
                    <form action="{{ route('admin.delete-promoter', $promoter->id) }}" method="post">
                      @csrf
                      @method('delete')
                      <button onclick="confirmDelete(event)">Delete</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center text-2xl text-white">No promoters found</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <script>
    function confirmDelete(event) {
      event.preventDefault();
      if (confirm("Are you sure?")) {
        event.target.closest('form').submit();
      }
    }
  </script>
</x-app-layout>
