<x-app-layout>
  <x-slot name="header">
    <x-sub-nav :userId="$userId" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div
        class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg border border-white bg-yns_dark_gray px-8 py-8 text-white">
        <div class="header border-b border-b-white">
          <div class="mb-4 flex justify-between space-x-4">
            <h1 class="font-heading text-4xl font-bold">Add user to {{ $service->name }}</h1>
            <div class="group flex gap-4">
              <x-text-input id="user-search" placeholder="Search for a user" class="rounded border p-2" />
            </div>
          </div>
        </div>
        <table id="userTable" class="w-full border border-white text-left font-sans rtl:text-right">
          <thead class="text-white underline dark:bg-black">
            <tr>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Name
              </th>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Email
              </th>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Actions
              </th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</x-app-layout>

<script>
  $(document).ready(function() {
    let debounceTimer;

    $('#user-search').on('input', function() {
      const query = $(this).val();
      const dashboardType = "{{ $dashboardType }}";

      if (debounceTimer) {
        clearTimeout(debounceTimer);
      }

      debounceTimer = setTimeout(function() {
        if (!query) {
          $('#userTable tbody').empty();
          return;
        }

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $('#userTable tbody').html('<tr><td colspan="3" class="text-center">Loading...</td></tr>');

        $.ajax({
          url: "{{ route('admin.dashboard.search-users', ['dashboardType' => $dashboardType]) }}",
          method: "GET",
          data: {
            query: query,
          },
          success: function(data) {
            console.log(data);
            $('#userTable tbody').empty();
            if (Array.isArray(data.result) && data.result.length > 0) {
              data.result.forEach(function(user) {
                $('#userTable tbody').append(`
                  <tr class="odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-black even:dark:bg-gray-900">
                    <td class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">${user.name}</td>
                    <td class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">${user.email}</td>
                    <td class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
                      <x-button class="addUserBtn" label="Add" data-user-id="${user.id}">Add</x-button>
                    </td>
                  </tr>
                `);
              });
            } else {
              $('#userTable tbody').append(
                '<tr><td colspan="3" class="text-center">No users found.</td></tr>');
            }
          },
          error: function(xhr) {
            console.error(xhr.responseJSON.message || 'Error retrieving users.');
            $('#userTable tbody').html(
              '<tr><td colspan="3" class="text-center">Error retrieving users.</td></tr>');
          }
        });
      }, 300);
    });

    $('#userTable').on('click', '.addUserBtn', function() {
      const userId = $(this).data('user-id');
      const dashboardType = "{{ $dashboardType }}";
      const currentServiceId = "{{ $currentServiceId }}";

      $.ajax({
        url: "{{ route('admin.dashboard.link-user', ['dashboardType' => ':dashboardType', 'id' => ':id']) }}"
          .replace(':dashboardType', dashboardType).replace(':id', userId),
        type: 'POST',
        data: {
          user_id: userId,
          currentServiceId: currentServiceId,
        },
        success: function(response) {
          showSuccessNotification(response.message);
        },
        error: function(xhr) {
          showFailureNotification('Error adding user: ' + xhr.responseJSON.message)
        }
      });
    });
  });
</script>
