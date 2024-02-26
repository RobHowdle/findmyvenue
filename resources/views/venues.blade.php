<x-guest-layout>
  <x-slot name="header">
    <h1 class="text-center font-heading text-6xl text-white">Venues
      {{ __('Venues') }}
    </h1>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <h1 class="text-center font-heading text-6xl text-white">Venues</h1>
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
                Promoter
              </th>
            </tr>
          </thead>
          <tbody>
            @foreach ($venues as $venue)
              <tr
                class="border-b odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-gray-900 even:dark:bg-gray-800">
                <th scope="row" class="whitespace-nowrap px-6 py-4 font-sans text-xl text-white">
                  @if ($venue->extraInfo)
                    <a href="#" class="venue-link" data-venue-id="{{ $venue->id }}">{{ $venue->name }}</a>
                  @else
                    {{ $venue->name }}
                  @endif
                </th>
                <td class="whitespace-nowrap px-6 py-4 font-sans text-xl text-white">
                  {{ $venue->location }}
                </td>
                <td class="whitespace-nowrap px-6 py-4 font-sans text-xl text-white">
                  {{ $venue->contact_name ?? 'N/A' }}
                </td>
                <td class="whitespace-nowrap px-6 py-4 font-sans text-xl text-white">
                  {{ $venue->promoter->name ?? 'N/A' }}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <div class="modal" id="venueModal" data-venue-id="">
          <div class="modal-content px-6 py-4">
            <div class="modal-header flex justify-end">
              <span class="close fas fa-times"></span>
            </div>
            <div id="modalContent">
              <h3 class="mb-4 text-center font-heading text-2xl text-white underline">More Info</h3>
              <p class="text font-sans text-white"></p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</x-guest-layout>
{{-- 
@push('scripts')
  {{ $dataTable->scripts() }} 
@endpush --}}

<script>
  $(document).ready(function() {
    $(document).on('click', '.venue-link', function(e) {
      e.preventDefault();
      var venueId = $(this).data("venue-id");
      $('#venueModal').attr('data-venue-id', venueId);
      $.ajax({
        url: "/api/venues/" + venueId,
        type: "GET",
        success: function(response) {
          $("#modalContent p").html(response.text);
          $("#venueModal").addClass('modal-visible');
        },
        failure: function(e) {
          console.log(e);
        }
      });
    });

    $(document).on('click', '.close', function() {
      closeModal();
    });
  });

  function closeModal() {
    $(".modal").removeClass("modal-visible");
  }

  $(document).keydown(function(event) {
    if (event.keyCode === 27 && $(".modal").hasClass("modal-visible")) {
      closeModal();
    }
  });
</script>
