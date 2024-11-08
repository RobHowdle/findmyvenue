<section>
  <header>
    <h2 class="text-md font-heading font-medium text-white">
      {{ __('Change your user details') }}
    </h2>
  </header>
  <form method="POST" action="{{ route('profile.update', ['dashboardType' => $dashboardType, 'user' => $user->id]) }}"
    class="mt-6 space-y-6">
    @csrf
    @method('PUT')
    <div>
      <x-input-label for="firstName" :value="__('First Name')" />
      <x-text-input id="firstName" class="mt-1 block w-full" type="text" name="firstName" :value="old('firstName', $firstName ?? '')" required
        autofocus autocomplete="firstName" />
      <x-input-error :messages="$errors->get('firstName')" class="mt-2" />
    </div>
    <div class="mt-4">
      <x-input-label for="lastName" :value="__('Last Name')" />
      <x-text-input id="lastName" class="mt-1 block w-full" type="text" name="lastName" :value="old('lastName', $lastName ?? '')" required
        autofocus autocomplete="lastName" />
      <x-input-error :messages="$errors->get('lastName')" class="mt-2" />
    </div>

    <div class="mt-4">
      <x-input-label for="email" :value="__('Email')" />
      <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email', $email ?? '')" required
        autocomplete="username" />
      <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div class="mt-4">
      <x-input-label for="password" :value="__('Password')" />
      <x-text-input id="password" class="mt-1 block w-full" type="password" name="password"
        autocomplete="new-password" />
      <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <div class="mt-4">
      <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
      <x-text-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation"
        autocomplete="new-password" />
      <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>

    <div>
      <x-input-label for="location" :value="__('Location')" />
      <x-text-input id="location" class="mt-1 block w-full" type="text" name="location" :value="old('location', $location ?? '')" autofocus
        autocomplete="location" />
      <x-input-error :messages="$errors->get('location')" class="mt-2" />
    </div>

    <!-- Hidden fields to store the coordinates -->
    <label>Lat</label>
    <input type="text" id="address-latitude" name="latitude" class="text-black"
      value="{{ old('latitude', $user->latitude ?? '') }}">

    <label>Lon</label>
    <input type="text" id="address-longitude" name="longitude" class="text-black"
      value="{{ old('longitude', $user->longitude ?? '') }}">



    <div class="mt-4 grid grid-cols-2 items-center gap-4">
      <!-- Left Column: Current Roles -->
      <div>
        <x-input-label-dark>Current Role(s):</x-input-label-dark>
        @foreach ($userRole as $role)
          <p class="capitalize">{{ $role->name }}</p>
        @endforeach
      </div>

      <!-- Right Column: Edit and Add Buttons -->
      <div class="flex flex-col justify-start space-y-2">
        <x-input-label-dark>Actions</x-input-label-dark>
        <div class="role-buttons-container">
          @foreach ($userRole as $role)
            <div class="flex space-x-2">
              <x-button type="button" id="edit-role" data-role-id="{{ $role->id }}"
                data-role-name="{{ $role->name }}" label="Edit"></x-button>
              <x-button type="button" id="add-role" label="Add Role"></x-button>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <div id="editRoleModal" class="fixed inset-0 flex hidden items-center justify-center bg-gray-500 bg-opacity-75">
      <div class="rounded bg-black p-4 text-white shadow-md">
        <h2 id="editModalTitle" class="text-lg font-semibold">Edit Role</h2>
        <input type="hidden" id="edit-role-id" />
        <div class="mt-2">
          <x-input-label for="role" :value="__('Role')" />
          <select id="role" name="role"
            class="mt-1 block w-full rounded-md border-yns_red capitalize shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600"
            autofocus autocomplete="role">
            @foreach ($roles as $role)
              <option value="{{ $role->id }}" {{ $userRole->first()->id == $role->id ? 'selected' : '' }}>
                {{ $role->name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="mt-4">
          <button id="save-edit-role-btn" class="rounded bg-blue-500 px-4 py-2 text-white">Save Changes</button>
          <button onclick="closeModal('editRoleModal')" class="rounded bg-gray-300 px-4 py-2 text-black">Cancel</button>
        </div>
      </div>
    </div>

    <div id="addRoleModal" class="fixed inset-0 flex hidden items-center justify-center bg-gray-500 bg-opacity-75">
      <div class="rounded bg-white p-4 shadow-md">
        <h2 id="addModalTitle" class="text-lg font-semibold">Add Role</h2>
        <input type="hidden" id="add-role-id" />
        <div class="mt-2">
          <label for="role-name">Role Name:</label>
          <input type="text" id="role-name" class="mt-1 block w-full rounded border p-2" />
        </div>
        <div class="mt-4">
          <button id="save-add-role-btn" class="rounded bg-blue-500 px-4 py-2 text-white">Add Role</button>
          <button onclick="closeModal('addRoleModal')"
            class="rounded bg-gray-300 px-4 py-2 text-black">Cancel</button>
        </div>
      </div>
    </div>

    <div class="flex items-center gap-4">
      <button type="submit"
        class="mt-8 rounded-lg border border-white bg-white px-4 py-2 font-heading font-bold text-black transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">Save</button>
      @if (session('status') === 'profile-updated')
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
          class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
      @endif
    </div>
  </form>
</section>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const roleContainer = document.querySelector('.role-buttons-container');

    roleContainer.addEventListener('click', function(event) {
      const target = event.target.closest('button'); // Get the closest button element

      if (target) {
        console.log("Button clicked:", target.id); // Log the clicked button ID
        const roleId = target.dataset.roleId || ''; // Get the role ID if it exists
        const roleName = target.dataset.roleName || ''; // Get the role name if it exists

        if (target.id === 'edit-role') {
          console.log("Editing role:", roleName); // Log role being edited
          showEditRoleModal(roleId, roleName);
        } else if (target.id === 'add-role') {
          console.log("Adding role"); // Log add role action
          showAddRoleModal();
        }
      }
    });

    function showEditRoleModal(roleId, roleName) {
      const editModalTitle = document.getElementById('editModalTitle');
      const editRoleInput = document.getElementById('edit-role-name');
      const saveEditButton = document.getElementById('save-edit-role-btn');

      editModalTitle.textContent = 'Edit Role';
      editRoleInput.value = roleName; // Set the existing role name
      document.getElementById('edit-role-id').value = roleId; // Store the role ID

      saveEditButton.onclick = function() {
        saveEditRole(roleId); // Define this function to handle saving the edited role
      };

      // Remove the hidden class to show the edit modal
      document.getElementById('editRoleModal').classList.remove('hidden');
    }

    function showAddRoleModal() {
      const addModalTitle = document.getElementById('addModalTitle');
      const roleInput = document.getElementById('role ');
      const saveAddButton = document.getElementById('save-add-role-btn');

      addModalTitle.textContent = 'Add Role';
      roleInput.value = ''; // Clear the input for new role
      document.getElementById('add-role-id').value = ''; // Clear the role ID

      saveAddButton.onclick = saveAddRole; // Set to add role function

      // Remove the hidden class to show the add modal
      document.getElementById('addRoleModal').classList.remove('hidden');
    }

    // Close modal function
    window.closeModal = function(modalId) {
      document.getElementById(modalId).classList.add('hidden');
    }
  });

  document.addEventListener("DOMContentLoaded", function() {
    const locationInput = document.getElementById("location"); // The location text input
    const latitudeField = document.getElementById("address-latitude");
    const longitudeField = document.getElementById("address-longitude");

    const geocoder = new google.maps.Geocoder();

    const autocomplete = new google.maps.places.Autocomplete(locationInput);
    autocomplete.addListener("place_changed", function() {
      const place = autocomplete.getPlace();

      if (!place.geometry) {
        alert("No details available for this location.");
        return;
      }

      // Set the input value to the selected place
      locationInput.value = place.formatted_address;
      console.log(locationInput.value);

      // Get the latitude and longitude from the place
      const lat = place.geometry.location.lat();
      const lng = place.geometry.location.lng();

      // Ensure the latitude and longitude values are correctly assigned to the right fields
      latitudeField.value = lng; // Latitude goes into the latitude field
      longitudeField.value = lat; // Longitude goes into the longitude field

      console.log("Latitude:", lat);
      console.log("Longitude:", lng);

      // Optionally, center the map and zoom in on the location
      if (place.geometry.viewport) {
        map.fitBounds(place.geometry.viewport);
      } else {
        map.setCenter(place.geometry.location);
        map.setZoom(17);
      }
    });

    // Initialize Google Map with a marker and center it
    const map = new google.maps.Map(document.getElementById('address-map'), {
      zoom: 13,
      center: {
        lat: 59.339024834494886,
        lng: 18.06650573462189
      }, // Default center if no location is selected
    });

    const marker = new google.maps.Marker({
      map: map,
      position: {
        lat: 59.339024834494886,
        lng: 18.06650573462189
      }, // Default marker
    });
  });
</script>
