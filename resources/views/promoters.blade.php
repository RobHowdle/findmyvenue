<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">Promoters
      {{ __('Promoters') }}
    </h1>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <h1 class="text-center font-heading text-6xl text-white">Promoters</h1>
      {{-- {{ $dataTable->table() }} --}}
      <div class="relative mt-4 overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full border-2 border-white text-left font-sans rtl:text-right">
          <thead class="text-2xl text-white underline">
            <tr>
              <th scope="col" class="px-6 py-3">
                Venue
              </th>
              <th scope="col" class="px-6 py-3">
                Location
              </th>
              <th scope="col" class="px-6 py-3">
                Contact
              </th>
              <th scope="col" class="px-6 py-3">
                Venue
              </th>
            </tr>
          </thead>
          <tbody>
            @foreach ($promoters as $promoter)
              <tr
                class="border-b odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-gray-900 even:dark:bg-gray-800">
                <th scope="row" class="whitespace-nowrap px-6 py-4 font-sans text-xl text-white">
                  <a href="{{ url('promoters', $promoter->id) }}"
                    class="venue-link hover:text-gray-500">{{ $promoter->name }}</a>
                </th>
                <td class="whitespace-nowrap px-6 py-4 font-sans text-xl text-white">
                  {{ $promoter->location }}
                </td>
                <td class="flex gap-4 whitespace-nowrap px-6 py-4 font-sans text-xl text-white">
                  @if ($promoter->contact_number || $promoter->contact_email || $promoter->contact_link ?? 'N/A')
                    @if ($promoter->contact_number)
                      <a class="" href="tel:{{ $promoter->contact_number }}"><span
                          class="fas fa-phone"></span></a>
                    @endif
                    @if ($promoter->contact_email)
                      <a href="mailto:{{ $promoter->contact_email }}"><span class="fas fa-envelope"></span></a>
                    @endif
                    @if ($promoter->platforms)
                      @foreach ($promoter->platforms as $platform)
                        @if ($platform['platform'] == 'facebook')
                          <a href="{{ $platform['url'] }}" target=_blank><span class="fab fa-facebook"></span></a>
                        @elseif($platform['platform'] == 'twitter')
                          <a href="{{ $platform['url'] }}" target=_blank><span class="fab fa-twitter"></span></a>
                        @elseif($platform['platform'] == 'instagram')
                          <a href="{{ $platform['url'] }}" target=_blank><span class="fab fa-instagram"></span></a>
                        @endif
                      @endforeach
                    @endif
                  @endif
                </td>
                <td class="whitespace-nowrap px-6 py-4 font-sans text-xl text-white">
                  @if ($promoter->venues)
                    @foreach ($promoter->venues as $promoter)
                      {{ $promoter['name'] }}
                    @endforeach
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

    </div>
  </div>
</x-guest-layout>
{{-- 
@push('scripts')
  {{ $dataTable->scripts() }} 
@endpush --}}
