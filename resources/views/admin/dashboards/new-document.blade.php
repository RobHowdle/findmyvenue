<x-app-layout>
  <x-slot name="header">
    <x-sub-nav :userId="$userId" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div class="grid grid-cols-[1.75fr_1.25fr] rounded-lg border border-white">
        <div class="rounded-l-lg border-r border-r-white bg-yns_dark_gray px-8 py-8">
          <p class="mb-10 text-4xl font-bold text-white">New Document</p>
          @include('admin.dashboards.forms.new-document-form', [
              'serviceableId' => $serviceableId,
              'services' => $services,
              'serviceableType' => $serviceableType,
          ])
          <div class="dropzone-container rounded-lg border border-yns_red bg-yns_dark_blue">
            <form action="{{ route('admin.dashboard.document.file.upload') }}" class="dropzone bg-transparent"
              id="my-dropzone">
              <div class="dz-message" data-dz-message>
                <span>Drag and drop files here or click to upload</span>
              </div>
              <input type="hidden" name="serviceable_id" value="{{ $serviceableId }}">
              <input type="hidden" name="serviceable_type" value="{{ $serviceableType }}">
              <input type="hidden" id="uploaded_file_path" name="uploaded_file_path">
            </form>
          </div>
        </div>

        <div class="bg-yns_dark_blue px-8 py-8">
          <p class="mb-6 text-4xl font-bold text-white">Preview</p>
          <div id="preview-container" class="dz-preview dz-file-preview">
            <div class="dz-details">
              <div class="dz-filename"><span data-dz-name></span></div>
              <img data-dz-thumbnail />
            </div>
            <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
            <div class="dz-error-message"><span data-dz-errormessage></span></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
