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

    <div class="group hidden">
      <label>Lat</label>
      <input type="text" id="address-latitude" name="latitude" class="hidden text-black"
        value="{{ old('latitude', $user->latitude ?? '') }}">
    </div>

    <div class="group hidden">
      <label>Lon</label>
      <input type="text" id="address-longitude" name="longitude" class="hidden text-black"
        value="{{ old('longitude', $user->longitude ?? '') }}">
    </div>


    <div class="mt-4">
      <!-- Table to Display Current Roles -->
      <table id="role-table" class="min-w-full table-auto border-collapse">
        <thead>
          <tr>
            <th class="px-4 py-2 text-left">Current Role(s)</th>
            <th class="px-4 py-2 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($userRole as $role)
            <tr class="border-b">
              <td class="px-4 py-2 capitalize">{{ $role->name }}</td>
              <td class="px-4 py-2">
                <x-button type="button" id="edit-role" data-role-id="{{ $role->id }}"
                  data-role-name="{{ $role->name }}" label="Edit"></x-button>
                <x-button type="button" id="delete-role" data-role-id="{{ $role->id }}"
                  data-role-name="{{ $role->name }}" label="Delete"></x-button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div class="mt-4">
        <x-button type="button" id="add-role" label="Add Role"></x-button>
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
          <button onclick="closeModal('editRoleModal')"
            class="rounded bg-gray-300 px-4 py-2 text-black">Cancel</button>
        </div>
      </div>
    </div>

    @php
      $userRoleIds = $userRole->pluck('id')->toArray();
    @endphp

    <div id="addRoleModal" class="fixed inset-0 flex hidden items-center justify-center bg-gray-500 bg-opacity-75">
      <div class="w-96 rounded bg-black p-8 text-white shadow-md">
        <h2 id="addModalTitle" class="text-lg font-semibold">Add Role</h2>
        <input type="hidden" id="add-role-id" />
        <div class="mt-2">
          <label for="role-name">Select Role</label>
          <select id="new-role" name="role"
            class="mt-1 block w-full rounded-md border-yns_red shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600"
            required autofocus autocomplete="role">
            @foreach ($roles as $role)
              <option value="{{ $role->id }}" @if (in_array($role->id, $userRoleIds)) disabled @endif>
                {{ ucfirst($role->name) }}</option>
            @endforeach
          </select>
          <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>
        <div class="mt-4 flex flex-row gap-6">
          <x-button id="save-add-role-btn" label="Add Role"></x-button>
          <x-button onclick="closeModal('addRoleModal')" label="Cancel"></x-button>
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
    const addRoleBtn = document.getElementById('add-role');
    if (addRoleBtn) {
      addRoleBtn.addEventListener('click', showAddRoleModal);
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
    const saveAddButton = document.getElementById('save-add-role-btn');
    const dashboardType = "{{ $dashboardType }}";
    const userId = "{{ $user->id }}";

    addModalTitle.textContent = 'Add Role';

    saveAddButton.onclick = function() {
      const roleSelect = document.getElementById('new-role'); // Get the role select element
      saveAddRole(roleSelect.value);
    };

    // Remove the hidden class to show the add modal
    document.getElementById('addRoleModal').classList.remove('hidden');
  }

  // Close modal function
  window.closeModal = function(modalId) {
    document.getElementById(modalId).classList.add('hidden');
  }

  function saveAddRole(roleSelect) {
    const dashboardType = "{{ $dashboardType }}";
    const userId = "{{ $user->id }}";

    if (!roleSelect) {
      showFailureNotification('Please select a role');
      return;
    }

    fetch(`/profile/${dashboardType}/${userId}/add-role`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({
          id: userId,
          roleId: roleSelect,
          dashboardType: dashboardType,
        }),
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Close the modal
          closeModal('addRoleModal');
          showSuccessNotification(data.message);

          // Update the roles table dynamically
          const roleTableBody = document.querySelector('#role-table tbody');

          // Create a new row for the added role
          const newRow = document.createElement('tr');
          newRow.classList.add('border-b');
          newRow.innerHTML = `
                <td class="px-4 py-2 capitalize">${data.newRoleName}</td>
                <td class="px-4 py-2">
                    <x-button type="button" id="edit-role" data-role-id="${data.newRoleId}" data-role-name="${data.newRoleName}" label="Edit"></x-button>
                </td>
            `;

          // Append the new row to the table
          roleTableBody.appendChild(newRow);
        } else {
          showFailureNotification(data.message || 'Failed to add role');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showFailureNotification('An error occurred while adding the role');
      });
  }

  // Example removeRole function (if you want to remove the role later)
  function removeRole(userId, roleId) {
    // Logic for removing the role, which can involve another AJAX call to the backend
    console.log(`Removing role ${roleId} for user ${userId}`);
  }


  function saveEditRole(roleId) {
    const newRole = document.getElementById('role').value;
    const dashboardType = "{{ $dashboardType }}";
    const userId = "{{ $user->id }}";

    fetch(`/profile/${dashboardType}/${userId}/edit-role`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({
          roleId: roleId,
          newRole: newRole,
        }),
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Close the modal
          closeModal('editRoleModal');
          showSuccessNotification(data.message);

          // Add new row to the table
          const table = document.getElementById('role-table'); // Assuming your table has this ID
          const newRow = table.insertRow(); // Insert a new row

          // Insert cells into the row and populate with the new role data
          const roleCell = newRow.insertCell(0);
          const actionCell = newRow.insertCell(1);

          roleCell.textContent = newRole; // Populate with the new role name

          // You can also add an action button (e.g., delete/edit) to this row
          const editButton = document.createElement('button');
          editButton.textContent = 'Edit';
          editButton.className = 'btn btn-warning'; // Add your button classes here
          editButton.onclick = () => {
            // Trigger the edit role modal, passing the current role data
            openEditRoleModal(roleId, newRole);
          };

          actionCell.appendChild(editButton);

          // Optional: You may also want to clear the form or reset the inputs
          document.getElementById('role').value = ''; // Reset the role input field
        } else {
          showFailureNotification(data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showFailureNotification('An unexpected error occurred: ' + error.message);
      });
  }


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
