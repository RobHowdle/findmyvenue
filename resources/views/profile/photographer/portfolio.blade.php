<header>
  <h2 class="text-md font-heading font-medium text-white">
    {{ __('Upload a max of 6 images to showcase your work, add a URL to your main portfolio') }} </h2>
</header>

<div class="mb-8 grid grid-cols-3 items-center gap-4 space-y-6">
  @foreach ($portfolioImages as $image)
    <div class="overflow-hidden rounded shadow-md">
      <img src="{{ asset('storage/' . $image) }}" alt="Portfolio Image" class="h-auto w-full">
    </div>
  @endforeach
</div>

<!-- Main form where portfolio link and uploaded file path are saved -->
@include('admin.dashboards.forms.portfolio-images-form', [
    'dashboardType' => $dashboardType,
])

<!-- Dropzone form for file upload -->
<form action="{{ route('photographer.upload', ['dashboardType' => $dashboardType]) }}"
  class="dropzone mt-8 bg-transparent" id="my-dropzone">
  @csrf
  <div class="dz-message" data-dz-message>
    <span>Drag and drop files here or click to upload</span>
  </div>
  <input type="hidden" id="portfolio_image_path" name="portfolio_image_path">
  <input type="hidden" name="serviceable_id" value="{{ $photographerData['serviceableId'] }}">
  <input type="hidden" name="serviceable_type" value="{{ $photographerData['serviceableType'] }}">
</form>

<script>
  // Dropzone configuration
  Dropzone.autoDiscover = false;

  document.addEventListener("DOMContentLoaded", function() {
    const myDropzone = new Dropzone("#my-dropzone", {
      paramName: "file", // The name that will be used to transfer the file
      maxFilesize: 2, // MB
      acceptedFiles: ".jpeg,.png,.jpg,.gif",
      addRemoveLinks: true, // Allows files to be removed
      init: function() {
        let uploadedFilePaths = []; // Initialize an array to hold the file paths

        // This event fires when a file has been successfully uploaded
        this.on("success", function(file, response) {
          // After a successful upload, add the file path to the array
          uploadedFilePaths.push(response.path);
          // Update the hidden input field with the JSON-encoded array of paths
          document.getElementById("portfolio_image_path").value = JSON.stringify(uploadedFilePaths);
        });

        // Optional: Handle removing files and update the hidden input accordingly
        this.on("removedfile", function(file) {
          let index = uploadedFilePaths.indexOf(file.previewElement.dataset.path);
          if (index >= 0) {
            uploadedFilePaths.splice(index, 1);
            document.getElementById("portfolio_image_path").value = JSON.stringify(uploadedFilePaths);
          }
        });
      },
    });
  });
</script>
