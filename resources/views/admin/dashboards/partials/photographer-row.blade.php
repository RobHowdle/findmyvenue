<tr class="even:bg-gray-600 odd:dark:bg-gray-800">
  <td
    class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
    {{ $photographer->name }}</td>

  <td
    class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
    <x-button class="join-photographer-btn" label="Join Photographer"
      data-photographer-id="{{ $photographer->id }}"></x-button>
  </td>
</tr>
