<p class="text-3xl text-white">Photographer Registration Form</p>

<div class="group relative z-0 mb-5 mt-3 w-full">
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
  <input type="text" name="photographer_name" id="photographer_name" value="{{ old('photographer_name') }}"
    class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
    placeholder=" " required />
  <label for="photographer_name"
    class="absolute top-3 -z-10 origin-[0] -translate-y-6 scale-75 transform text-sm text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:start-0 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:font-medium peer-focus:text-blue-600 rtl:peer-focus:left-auto rtl:peer-focus:translate-x-1/4 dark:text-gray-400 peer-focus:dark:text-blue-500">
    Name<span class="required">*</span>
  </label>
  @error('photographer_name')
    <span class="text-danger">{{ $message }}</span>
  @enderror
</div>

<div class="group relative z-0 mb-5 w-full">
  <input
    class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
    aria-describedby="photographer_logo" name="photographer_logo" id="photographer_logo" type="file">
  <label
    class="absolute top-3 -z-10 origin-[0] -translate-y-6 scale-75 transform text-sm text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:start-0 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:font-medium peer-focus:text-blue-600 rtl:peer-focus:translate-x-1/4 dark:text-gray-400 peer-focus:dark:text-blue-500"
    for="photographer_logo">Upload Logo</label>
  @error('photographer_logo')
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
    <input type="hidden" name="packages_json" id="packages-json">
    <div class="package-row mt-3 grid grid-cols-4 gap-4">
      <input type="text" name="package_title[]" placeholder="Package Title"
        class="peer col-span-1 block appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500">
      <input type="text" name="package_description[]" placeholder="Package Description"
        class="peer col-span-1 block appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500">
      <input type="number" name="package_cost[]" placeholder="Package Cost"
        class="peer col-span-1 block appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500">
      <button type="button"
        class="remove-package col-span-1 border border-white p-2 text-sm hover:bg-black hover:text-white">Remove
        Package</button>
    </div>
  </div>
  <button type="button" id="add-package" class="mt-4 border border-white p-2 hover:bg-black hover:text-white">Add
    Package</button>
</div>

<div class="group relative z-0 mb-5 w-full">
  <label class="text-sm font-medium text-gray-900 dark:text-gray-300">Preferred Work Environments</label>
  <div class="mt-4 grid grid-cols-3 gap-4">
    <div class="flex items-center">
      <input id="all-environments" name="environment_type[]" type="checkbox" value="all"
        class="all-environments-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
        {{ in_array('all', old('environment_type', [])) ? 'checked' : '' }} />
      <label for="all-types" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">All
        Types</label>
    </div>
    <div class="flex items-center">
      <input id="studio" name="environment_type[]" type="checkbox" value="studio"
        class="all-environments-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
        {{ in_array('studio', old('environment_type', [])) ? 'checked' : '' }} />
      <label for="studio" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Studio</label>
    </div>
    <div class="flex items-center">
      <input id="outdoors" name="environment_type[]" type="checkbox" value="outdoors"
        class="all-environments-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
        {{ in_array('outdoors', old('environment_type', [])) ? 'checked' : '' }} />
      <label for="ourdoors" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Outdoors</label>
    </div>
    <div class="flex items-center">
      <input id="indoors-low-light" name="environment_type[]" type="checkbox" value="indoors-low-light"
        class="all-environments-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
        {{ in_array('indoors-low-light', old('environment_type', [])) ? 'checked' : '' }} />
      <label for="indoors-low-light" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Indoors (Low
        Light)</label>
    </div>
    <div class="flex items-center">
      <input id="indoors-regular-light" name="environment_type[]" type="checkbox" value="indoors-regular-light"
        class="all-environments-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
        {{ in_array('indoors-regular-light', old('environment_type', [])) ? 'checked' : '' }} />
      <label for="indoors-regular-light" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Indoors
        (Regular Light)</label>
    </div>
  </div>
</div>

<div class="group relative z-0 mb-5 w-full">
  <label class="text-sm font-medium text-gray-900 dark:text-gray-300">Preferred Working Times</label>
  <div class="mt-4 grid grid-cols-3 gap-4">
    <div class="flex items-center">
      <input id="all-working-times" name="working_times[]" type="checkbox" value="all"
        class="all-working-times-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
        {{ in_array('all', old('working_times', [])) ? 'checked' : '' }} />
      <label for="all-types" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Any Time</label>
    </div>
    <div class="flex items-center">
      <input id="weekdays-any" name="working_times[]" type="checkbox" value="weekdays-any"
        class="all-working-times-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
        {{ in_array('weekdays-any', old('working_times', [])) ? 'checked' : '' }} />
      <label for="weekdays-any" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Weekdays (Any
        Time)</label>
    </div>
    <div class="flex items-center">
      <input id="weekdays-daytime" name="working_times[]" type="checkbox" value="weekdays-daytime"
        class="all-working-times-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
        {{ in_array('weekdays-daytime', old('working_times', [])) ? 'checked' : '' }} />
      <label for="weekdays-daytime" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Weekdays
        (Daytime)</label>
    </div>
    <div class="flex items-center">
      <input id="weekdays-evenings-nights" name="working_times[]" type="checkbox" value="weekdays-evenings-nights"
        class="all-working-times-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
        {{ in_array('weekdays-evenings-nights', old('working_times', [])) ? 'checked' : '' }} />
      <label for="weekdays-any" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Weekdays (Evenings/
        Nights)</label>
    </div>
    <div class="flex items-center">
      <input id="weekends-any" name="working_times[]" type="checkbox" value="weekends-any"
        class="all-working-times-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
        {{ in_array('weekends-any', old('working_times', [])) ? 'checked' : '' }} />
      <label for="weekends-any" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Weekends
        (Anytime)</label>
    </div>
    <div class="flex items-center">
      <input id="weekends-daytime" name="working_times[]" type="checkbox" value="weekends-daytime"
        class="all-working-times-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
        {{ in_array('weekends-daytime', old('working_times', [])) ? 'checked' : '' }} />
      <label for="weekdays-any" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Weekends
        (Evenings/Nights)</label>
    </div>
    <div class="flex items-center">
      <input id="weekends-evenings-nights" name="working_times[]" type="checkbox" value="weekends-evenings-nights"
        class="all-working-times-checkbox focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800"
        {{ in_array('weekends-evenings-nights', old('working_times', [])) ? 'checked' : '' }} />
      <label for="weekdays-any" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Weekends
        (Evenings/Nights)</label>
    </div>
  </div>
</div>

<div class="group relative z-0 mb-5 w-full">
  <input type="text" name="contact_number" id="contact_number" value="{{ old('contact_number') }}"
    class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
    placeholder=" " required />
  <label for="contact_number"
    class="absolute top-3 -z-10 origin-[0] -translate-y-6 scale-75 transform text-sm text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:start-0 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:font-medium peer-focus:text-blue-600 rtl:peer-focus:left-auto rtl:peer-focus:translate-x-1/4 dark:text-gray-400 peer-focus:dark:text-blue-500">
    Contact Number
  </label>
  @error('contact_number')
    <span class="text-danger">{{ $message }}</span>
  @enderror
</div>

<div class="group relative z-0 mb-5 w-full">
  <input type="text" name="contact_email" id="contact_email" value="{{ old('contact_email') }}"
    class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
    placeholder=" " required />
  <label for="contact_email"
    class="absolute top-3 -z-10 origin-[0] -translate-y-6 scale-75 transform text-sm text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:start-0 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:font-medium peer-focus:text-blue-600 rtl:peer-focus:left-auto rtl:peer-focus:translate-x-1/4 dark:text-gray-400 peer-focus:dark:text-blue-500">
    Contact Email
  </label>
  @error('contact_email')
    <span class="text-danger">{{ $message }}</span>
  @enderror
</div>

<div class="group relative z-0 mb-5 w-full">
  <input type="text" name="contact_links" id="contact_links" value="{{ old('contact_links') }}"
    class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500"
    placeholder=" " required />
  <label for="contact_links"
    class="absolute top-3 -z-10 origin-[0] -translate-y-6 scale-75 transform text-sm text-gray-500 duration-300 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-focus:start-0 peer-focus:-translate-y-6 peer-focus:scale-75 peer-focus:font-medium peer-focus:text-blue-600 rtl:peer-focus:left-auto rtl:peer-focus:translate-x-1/4 dark:text-gray-400 peer-focus:dark:text-blue-500">
    Contact Links (Separate by comma)
  </label>
  @error('contact_links')
    <span class="text-danger">{{ $message }}</span>
  @enderror
</div>
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
      <button type="button" class="remove-package col-span-1 border border-white p-2 text-sm hover:bg-black hover:text-white">Remove Package</button>
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

  // Event handler for "All Environments" checkbox
  document.getElementById('all-environments').addEventListener('change', function() {
    var checkboxes = document.querySelectorAll('.all-environments-checkbox');
    checkboxes.forEach(function(checkbox) {
      checkbox.checked = this.checked;
    }, this);
  });

  // Event handler for "All Working Times" checkbox
  document.getElementById('all-working-times').addEventListener('change', function() {
    var checkboxes = document.querySelectorAll('.all-working-times-checkbox');
    checkboxes.forEach(function(checkbox) {
      checkbox.checked = this.checked;
    }, this);
  });

  // Converting Package Fields to Json
  function formDataToJSON(formData) {
    var jsonObject = {};
    for (const [key, value] of formData.entries()) {
      jsonObject[key] = value;
    }
    return jsonObject;
  }

  // Get all the package rows and convert them to JSON
  function getPackagesJSON() {
    console.log('fire');
    var packageRows = document.querySelectorAll('.package-row');
    var packages = [];
    packageRows.forEach(function(row) {
      var title = row.querySelector('[name="package_title[]"]').value;
      var description = row.querySelector('[name="package_description[]"]').value;
      var cost = row.querySelector('[name="package_cost[]"]').value;
      packages.push({
        "title": title,
        "description": description,
        "cost": cost
      });
    });
    return packages;
  }

  // Form submission listener
  document.querySelector('form[action="{{ route('admin.save-other') }}"]').addEventListener('submit', function(event) {
    event.preventDefault();

    var packagesArray = getPackagesJSON();
    document.querySelector('#packages-json').value = JSON.stringify(packagesArray);
    console.log('Packages:', packagesArray);


    this.submit();
  });
</script>
