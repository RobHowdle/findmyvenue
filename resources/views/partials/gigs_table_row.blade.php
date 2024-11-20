<tr class="odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-black even:dark:bg-gray-900">
  <td
    class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
    <h3>{{ $event->event_name }}</h3>
  </td>
  <td
    class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
    {{-- {{ $event }} --}}
    {{-- @if ($event->venues->isNotEmpty())
      <p>{{ $event->venues->first()->location }}</p>
    @else
      <p>Location not available</p>
    @endif --}}
  </td>
  <td
    class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
    {{-- <p>{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y, g:i a') }}</p> --}}
  </td>
  <td
    class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
    {{-- <p>{{ $distance }}</p> <!-- Distance column --> --}}
  </td>
</tr>
