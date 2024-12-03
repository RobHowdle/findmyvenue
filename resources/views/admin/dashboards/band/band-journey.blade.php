<x-app-layout :dashboardType="$dashboardType" :modules="$modules">
  <x-slot name="header">
    <x-sub-nav :userId="$userId" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div
        class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg bg-yns_dark_gray px-16 py-12 text-center text-white">
        <h1 class="mb-8 text-2xl font-bold">Find/ Create Your Band</h1>

        @if (session('success'))
          <div class="mb-4 rounded bg-green-200 p-4 text-green-800">
            {{ session('success') }}
          </div>
        @endif

        <div class="group mb-4 text-left">
          <x-input-label-dark>If your band is already on the system, search for it!</x-input-label-dark>
          <x-text-input id="bandSearch" placeholder="Search for bands..." />
        </div>

        <div class="group mb-4">
          <h2 class="mb-4 text-xl font-semibold">Available Bands</h2>
          <table class="w-full border border-white text-left font-sans rtl:text-right" id="bandsTable">
            <thead class="underline">
              <tr>
                <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">
                  Band Name</th>
                <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">
                  Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <p id="noBandsMessage" class="mt-4 hidden">No bands available to join at the moment.</p>
        </div>

        <h2 class="mb-4 text-xl font-semibold">Not seeing your band? Create a New Band!</h2>
        <form action="{{ route('band.create', ['dashboardType' => $dashboardType]) }}" method="POST" class="mb-4">
          @csrf
          <div class="mb-4">
            <label for="band_name" class="mb-1 block text-left text-sm font-medium">Band Name:</label>
            <x-text-input name="band_name" id="band_name" required placeholder="Enter band name" />
          </div>
          <button type="submit" class="rounded bg-green-500 px-3 py-1 text-white hover:bg-green-600">Create
            Band</button>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>

<script>
  const dashboardType = "{{ $dashboardType }}";

  $(document).ready(function() {
    function fetchBands(query = '') {
      $.ajax({
        url: `/${dashboardType}/band-search`,
        method: 'GET',
        data: {
          query: query
        },
        success: function(data) {
          if (data.html.trim() === '') {
            $('#bandsTable tbody').empty();
            $('#noBandsMessage').removeClass('hidden');
          } else {
            $('#bandsTable tbody').html(data.html);
            $('#noBandsMessage').addClass('hidden');
          }
        }
      });
    }

    // Initial fetch of bands
    fetchBands();

    // Search functionality
    $('#bandSearch').on('keyup', function() {
      let query = $(this).val();
      fetchBands(query);
    });

    // Join band functionality
    $(document).on('click', '.join-band-btn', function(e) {
      e.preventDefault();
      let bandId = $(this).data('band-id');

      $.ajax({
        url: `/${dashboardType}/band-journey/join/${bandId}`,
        method: 'POST',
        data: {
          band_id: bandId,
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          if (response.success) {
            showSuccessNotification(response.message);
            window.location.href = response.redirect;
          }
        },
        error: function(xhr) {
          let errorMessage = xhr.responseJSON.message || 'Something went wrong!';
          showFailureNotification(errorMessage);
        }
      });
    });
  });
</script>
