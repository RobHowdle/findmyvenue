@foreach ($gigsCloseToMe as $event)
  <tr class="odd:bg-black even:bg-gray-900 dark:border-gray-700">
    <td class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 md:px-6 md:py-2 lg:px-8 lg:py-4">
      {{ $event->event_name ?? 'No name' }}
    </td>
    <td class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 md:px-6 md:py-2 lg:px-8 lg:py-4">
      {{ $event->venue_location ?? 'Location unavailable' }}
    </td>
    <td class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 md:px-6 md:py-2 lg:px-8 lg:py-4">
      {{ $event->distance ?? 'N/A' }}
    </td>
    <td class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 md:px-6 md:py-2 lg:px-8 lg:py-4">
      {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('d M Y, g:i a') : 'Date not set' }}
    </td>
  </tr>
@endforeach
