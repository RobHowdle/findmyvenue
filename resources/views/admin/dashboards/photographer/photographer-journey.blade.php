<x-app-layout :dashboardType="$dashboardType" :modules="$modules">
  <x-slot name="header">
    <x-sub-nav :userId="$userId" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div
        class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg bg-yns_dark_gray px-16 py-12 text-center text-white">
        <h1 class="mb-8 text-2xl font-bold">Find/ Create Your Photography Company</h1>

        @if (session('success'))
          <div class="mb-4 rounded bg-green-200 p-4 text-green-800">
            {{ session('success') }}
          </div>
        @endif

        <div class="group mb-4 text-left">
          <x-input-label-dark>If your photography company is already on the system, search for it!</x-input-label-dark>
          <x-text-input id="photographerSearch" placeholder="Search for photographers..." />
        </div>

        <div class="group mb-4">
          <h2 class="mb-4 text-xl font-semibold">Available Photographers</h2>
          <table class="w-full border border-white text-left font-sans rtl:text-right" id="photographyTable">
            <thead class="underline">
              <tr>
                <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">
                  Photographer Name</th>
                <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">
                  Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <p id="noPhotographersMessage" class="mt-4 hidden">No photographers available to join at the moment.</p>
        </div>

        <h2 class="mb-4 text-xl font-semibold">Not seeing your photography company? Create a New One!</h2>
        <form action="{{ route('photographer.store', ['dashboardType' => $dashboardType]) }}" method="POST"
          class="mb-4">
          @csrf
          <div class="mb-4">
            <label for="photographer_name" class="mb-1 block text-left text-sm font-medium">Photographer Name:</label>
            <x-text-input name="photographer_name" id="photographer_name" required
              placeholder="Enter photographer name" />
          </div>
          <button type="submit" class="rounded bg-green-500 px-3 py-1 text-white hover:bg-green-600">Create
            Photographer</button>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>

<script>
  const dashboardType = "{{ $dashboardType }}";

  jQuery(document).ready(function() {
    function fetchPhotographers(query = '') {
      $.ajax({
        url: `/${dashboardType}/photographer-search`,
        method: 'GET',
        data: {
          query: query
        },
        success: function(data) {
          if (data.html.trim() === '') {
            jQuery('#photographyTable tbody').empty();
            jQuery('#noPhotographersMessage').removeClass('hidden');
          } else {
            jQuery('#photographyTable tbody').html(data.html);
            jQuery('#noPhotographersMessage').addClass('hidden');
          }
        }
      });
    }

    // Initial fetch of bands
    fetchPhotographers();

    // Search functionality
    jQuery('#photographerSearch').on('keyup', function() {
      let query = jQuery(this).val();
      fetchPhotographers(query);
    });

    // Join band functionality
    jQuery(document).on('click', '.join-photographer-btn', function(e) {
      e.preventDefault();
      let photographerId = jQuery(this).data('photographer-id');
      console.log(photographerId);

      $.ajax({
        url: `/${dashboardType}/photographer-journey/link/${photographerId}`,
        method: 'POST',
        data: {
          photographer_id: photographerId,
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          showSuccessNotification(response.message);
          setTimeout(function() {
            window.location.href = response.redirect_url;
          }, 3000);
        },
        error: function(xhr) {
          let errorMessage = xhr.responseJSON.message || 'Something went wrong!';
          showFailureNotification(errorMessage);
        }
      });
    });
  });
</script>
