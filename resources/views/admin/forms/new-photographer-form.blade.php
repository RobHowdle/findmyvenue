<p class="text-3xl text-white">New Photographer</p>

<div class="group relative z-0 mb-5 w-full">
  <input type="search" name="address-input" id="address-input" value="{{ old('address-input') }}"
    class="map-input peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
    placeholder=" " required />
  <label for="address-input"
    class="absolute top-3 -z-10 origin-[0] -translate-y-6 scale-75 transform text-sm text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:start-0 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:font-medium peer-focus:text-blue-600 rtl:peer-focus:translate-x-1/4 dark:text-gray-400 peer-focus:dark:text-blue-500">Location
    - Where are you based?<span class="required">*</span></label>
  @error('address-input')
    <span class="text-danger">{{ $message }}</span>
  @enderror
</div>

<div class="group relative z-0 mb-5 w-full">
  <input type="text" name="promoter_name" id="promoter_name" value="{{ old('promoter_name') }}"
    class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
    placeholder=" " required />
  <label for="promoter_name"
    class="absolute top-3 -z-10 origin-[0] -translate-y-6 scale-75 transform text-sm text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:start-0 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:font-medium peer-focus:text-blue-600 rtl:peer-focus:left-auto rtl:peer-focus:translate-x-1/4 dark:text-gray-400 peer-focus:dark:text-blue-500">
    Name<span class="required">*</span>
  </label>
  @error('promoter_name')
    <span class="text-danger">{{ $message }}</span>
  @enderror
</div>

<div id="address-map-container" style="width: 100%; height: 400px; display: none;">
  <div style="width: 100%; height: 100%;" id="address-map"></div>
</div>

<div class="group relative z-0 mb-5 hidden w-full">
  <input
    class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
    type="text" id="postal-town-input" name="postal-town-input" placeholder="Postal Town Input"
    value="{{ old('postal-town-input') }}">
  <input
    class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
    type="text" id="address-latitude" name="latitude" placeholder="Latitude" value="{{ old('latitude') }}">
  <input
    class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
    type="text" id="address-longitude" name="longitude" placeholder="Longitude" value="{{ old('longitude') }}">
</div>

<div class="group relative z-0 mb-5 w-full">
  <label>Packages - <span>What do you offer?</span></label>
  @error('photographer_packages')
    <span class="text-danger">{{ $message }}</span>
  @enderror

  <div id="package-container">
    <div class="package-row mt-3 grid grid-cols-4 gap-4">
      <input type="text" name="package_title[]" placeholder="Package Title"
        class="peer col-span-1 block appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500">
      <input type="text" name="package_description[]" placeholder="Package Description"
        class="peer col-span-1 block appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500">
      <input type="number" name="package_cost[]" placeholder="Package Cost"
        class="peer col-span-1 block appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500">
      <button type="button" class="remove-package col-span-1 text-sm">Remove Package</button>
    </div>
  </div>
</div>

<button type="button" id="add-package">Add Package</button>

<script>
  // Function to add a new package row
  function addPackageRow() {
    var packageContainer = document.getElementById('package-container');
    var packageRow = document.createElement('div');
    packageRow.classList.add('package-row.mt-3.grid.grid-cols-4.gap-4');
    packageRow.innerHTML = `
    <div class="package-row mt-3 grid grid-cols-4 gap-4">
      <input type="text" name="package_title[]" placeholder="Package Title"
        class="peer col-span-1 block appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500">
      <input type="text" name="package_description[]" placeholder="Package Description"
        class="peer col-span-1 block appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500">
      <input type="number" name="package_cost[]" placeholder="Package Cost"
        class="peer col-span-1 block appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500">
      <button type="button" class="remove-package col-span-1 text-sm">Remove Package</button>
    </div>
    `;
    packageContainer.appendChild(packageRow);
  }

  // Function to remove a package row
  function removePackageRow(event) {
    var packageRow = event.target.parentNode;
    packageRow.parentNode.removeChild(packageRow);
  }

  // Add event listener for adding a new package
  document.getElementById('add-package').addEventListener('click', addPackageRow);

  // Add event listener for removing a package
  document.addEventListener('click', function(event) {
    if (event.target && event.target.classList.contains('remove-package')) {
      removePackageRow(event);
    }
  });
</script>
