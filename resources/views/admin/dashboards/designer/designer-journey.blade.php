<x-app-layout :dashboardType="$dashboardType" :modules="$modules">
  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg bg-yns_dark_gray px-16 py-12 text-white">
        <p class="mb-3 text-3xl font-bold text-white">Oops, you're not linked to anywhere! Let's fix that!</p>
        <div class="mb-4 grid grid-cols-2 gap-x-8 gap-y-4">
          <div class="group">
            <x-input-label-dark>What is the name of your Design Company?
              <span id="result-count"></span>
            </x-input-label-dark>
            <x-text-input id="designer-search"></x-text-input>
            <ul class="mt-2 flex flex-col gap-4 rounded-lg" id="designer-results"></ul>
          </div>
        </div>

        <div class="mb-4">
          <div class="col-span-2" id="create-designer-form" style="display: none;">
            <p class="col-span-2 mb-3 font-bold">It looks like you're not already in the system - Let's get you added!
            </p>
            <form action="{{ route('designer.create', ['dashboardType' => $dashboardType]) }}"
              class="grid grid-cols-2 gap-x-8 gap-y-4" id="designer-create-form" method="POST"
              enctype="multipart/form-data">
              @csrf
              <x-google-address-picker id="location" name="location" label="Where are you based?"
                placeholder="Search for a location..." value="" latitude="" longitude=""
                dataId=""></x-google-address-picker>

              <div class="group">
                <x-input-label-dark>Promotions Company Name</x-input-label-dark>
                <x-text-input id="name" name="name" value="{{ old('name') }}"></x-text-input>
                @error('name')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
              </div>

              <div class="group">
                <x-input-label-dark>Logo</x-input-label-dark>
                <x-input-file id="promoter_logo" name="promoter_logo"></x-input-file>
                @error('promoter_logo')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
              </div>

              <div class="group">
                <x-input-label-dark>Tell us a bit about you</x-input-label-dark>
                <x-textarea-input class="w-full" id="description"
                  name="description">{{ old('description') }}</x-textarea-input>
                @error('description')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
              </div>

              <div class="group">
                <x-input-label-dark for="contact_number">Contact Number</x-input-label-dark>
                <x-text-input id="contact_number" name="contact_number" />
              </div>
              <div class="group">
                <x-input-label-dark for="contact_email">Contact Email</x-input-label-dark>
                <x-text-input id="contact_email" name="contact_email" />
              </div>
              <div class="group">
                <x-input-label-dark for="contact_link">Social Links</x-input-label-dark>
                <x-text-input id="contact_link" name="contact_link" />
              </div>

              <div class="group relative z-0 mb-5 w-full" x-data="{ isMainContact: 'true' }">
                <x-input-label-dark>Are you the main contact for the promoter?</x-input-label-dark>

                <!-- Radio buttons for Yes/No -->
                <div class="my-4 flex items-center gap-4">
                  <div class="flex items-center gap-2">
                    <x-input-radio id="main-contact-yes" name="is_main_contact" value="true"
                      x-model="isMainContact"></x-input-radio>
                    <x-input-label-dark for="main-contact-yes" class="mb-0">Yes</x-input-label-dark>
                  </div>
                  <div class="flex items-center gap-2">
                    <x-input-radio id="main-contact-no" name="is_main_contact" value="false"
                      x-model="isMainContact"></x-input-radio>
                    <x-input-label-dark for="main-contact-no" class="mb-0">No</x-input-label-dark>
                  </div>
                </div>

                <!-- Fields for main contact information -->
                <div x-show="isMainContact === 'false'" x-cloak class="mt-4">
                  <div class="mb-4">
                    <x-input-label-dark for="contact_name">Contact Name</x-input-label-dark>
                    <x-text-input id="contact_name" name="contact_name" />
                  </div>
                </div>

                <div class="group">
                  <button type="submit"
                    class="mt-8 flex w-full justify-center rounded-lg border border-yns_cyan bg-yns_cyan px-4 py-2 font-heading text-xl text-black transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">Save</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>

<script>
  jQuery(document).ready(function() {
    // Designer search functionality
    jQuery('#designer-search').on('keyup', function() {
      let query = jQuery(this).val();
      const dashboardType = "{{ $dashboardType }}";


      $.ajax({
        url: '{{ route('designer.search', ['dashboardType' => ':dashboardType']) }}'
          .replace(':dashboardType', dashboardType),
        type: 'GET',
        data: {
          query: query
        },
        success: function(data) {
          jQuery('#designer-results').html('');
          jQuery('#result-count').text('');

          if (data.count > 0) {
            jQuery('#create-designer-form').hide(); // Explicitly hide the form
            jQuery('#designer-results').html(data.results.map(designer => `
        <li class="flex px-2 flex-row items-center justify-between">
            <p>${designer.name}</p>
            <button class="bg-white text-black rounded-lg px-4 py-2 font-heading transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow" data-id="${designer.id}">
                Link
            </button>
        </li>
    `).join(''));
            jQuery('#result-count').text(`${data.count} results found`);
          } else {
            jQuery('#create-designer-form').show(); // Explicitly show the form
            jQuery('#designer-results').html('<li>No designer found</li>');
            jQuery('#result-count').text('0 results');
          }
        }
      });
    });

    // Event delegation for dynamically created buttons
    jQuery('#designer-results').on('click', 'button', function() {
      const designerId = jQuery(this).data('id'); // Retrieve the id from data-id attribute
      linkUserToDesigner(designerId); // Call your function
    });

    function linkUserToDesigner(designerId) {
      const dashboardType = "{{ $dashboardType }}";
      $.ajax({
        url: `/${dashboardType}/designer-journey/join/${designerId}`,
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          serviceable_id: designerId
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
    }
  });
</script>
