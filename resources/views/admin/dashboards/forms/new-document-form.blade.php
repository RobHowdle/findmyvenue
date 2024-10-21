<form id="document-form" method="POST" enctype="multipart/form-data">
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
      <x-input-label-dark>Category/Tag</x-input-label-dark>
      <x-text-input id="category" name="category"></x-text-input>
      @error('category')
        <p class="yns_red mt-1 text-sm">{{ $message }}</p>
      @enderror
    </div>

    <!-- Hidden input to store the uploaded file path -->
    <input type="hidden" id="uploaded_file_path" name="uploaded_file_path">

    <button type="submit"
      class="mt-8 flex w-full justify-center rounded-lg border border-yns_cyan bg-yns_cyan px-4 py-2 font-heading text-xl text-black transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">Upload
      Document</button>
  </div>
</form>
