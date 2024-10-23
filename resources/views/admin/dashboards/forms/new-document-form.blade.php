<form id="document-form" method="POST" action={{ route('admin.dashboard.store-document') }} enctype="multipart/form-data">
  @csrf
  <div class="mb-4 grid grid-cols-1 gap-x-8 gap-y-4">
    <div class="group">
      <x-input-label-dark>Document Title</x-input-label-dark>
      <x-text-input id="title" name="title" required></x-text-input>
      @error('title')
        <p class="yns_red mt-1 text-sm">{{ $message }}</p>
      @enderror
    </div>

    <div class="group">
      <x-input-label-dark>Description</x-input-label-dark>
      <x-textarea-input class="w-full" id="description" name="description"></x-textarea-input>
      @error('description')
        <p class="yns_red mt-1 text-sm">{{ $message }}</p>
      @enderror
    </div>

    <div class="group">
      <x-input-label-dark>Tags</x-input-label-dark>
      <div class="mb-4">
        <x-multi-select id="tags" name="tags[]" :options="[
            'EPK' => 'EPK',
            'Rider Instructions' => 'Rider Instructions',
            'Tech Spec' => 'Tech Spec',
            'Artwork' => 'Artwork',
            'Band Picture' => 'Band Picture',
            'Setlist' => 'Setlist',
        ]" />
      </div>
      @error('tags')
        <p class="yns_red mt-1 text-sm">{{ $message }}</p>
      @enderror
    </div>

    <input type="hidden" id="uploaded_file_path" name="uploaded_file_path">

    <button type="submit"
      class="mt-8 flex w-full justify-center rounded-lg border border-yns_cyan bg-yns_cyan px-4 py-2 font-heading text-xl text-black transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">Upload
      Document</button>
  </div>
</form>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    jQuery("#tags").select2({
      tags: true, // Allow user to type custom tags
      tokenSeparators: [","]
    });

    // Handle form submission
    jQuery("#document-form").on("submit", function(event) {
      event.preventDefault(); // Prevent the default form submission

      // Serialize form data
      var formData = new FormData(this);

      // Send AJAX request
      jQuery.ajax({
        url: jQuery(this).attr('action'), // Form action URL
        type: 'POST', // HTTP method
        data: formData, // Form data
        contentType: false, // Important: Prevent jQuery from setting content type
        processData: false, // Important: Prevent jQuery from processing the data
        success: function(response) {
          // Handle success response
          showSuccessNotification(response);

          // Optionally, redirect or clear form fields
        },
        error: function(xhr) {
          // Handle error response
          var errors = xhr.responseJSON.errors;
          var errorMessages = Object.values(errors).flat().join("\n");
          showFailureNotification(error.messages);
        }
      });
    });
  });
</script>
