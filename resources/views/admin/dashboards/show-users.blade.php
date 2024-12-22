<x-app-layout :dashboardType="$dashboardType" :modules="$modules">
  <x-slot name="header">
    <x-sub-nav :userId="$userId" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div
        class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg border border-white bg-yns_dark_gray px-8 py-8 text-white">
        <div class="header border-b border-b-white">
          <div class="mb-4 flex justify-between space-x-4">
            <h1 class="font-heading text-4xl font-bold">Users</h1>
            <a href="{{ route('admin.dashboard.new-user', ['dashboardType' => $dashboardType]) }}"
              class="rounded-lg bg-white px-4 py-2 font-bold text-black transition duration-150 ease-in-out hover:text-yns_yellow">Add
              User</a>
          </div>
        </div>

        <table class="w-full border border-white text-left font-sans rtl:text-right" id="otherServices">
          <thead class="text-white underline dark:bg-black">
            <tr>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Name
              </th>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Email
              </th>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Role
              </th>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Date
                Added</th>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Actions
              </th>
            </tr>
          </thead>
          <tbody id="user-list">
          </tbody>
        </table>
      </div>
    </div>
  </div>
</x-app-layout>
<script>
  jQuery(document).ready(function() {
    const dashboardType = "{{ $dashboardType }}";

    function fetchUsers() {
      $.ajax({
        url: '{{ route('admin.dashboard.get-users', ['dashboardType' => '__dashboardType__']) }}'.replace(
          '__dashboardType__', dashboardType),
        type: 'GET',
        success: function(response) {
          console.log(response);

          const userList = jQuery('#user-list');
          userList.empty();

          // Check if relatedUsers exists and has entries
          if (Array.isArray(response.relatedUsers) && response.relatedUsers.length > 0) {
            response.relatedUsers.forEach(service => {
              // Check if linked_users exist within each service
              if (Array.isArray(service.linked_users) && service.linked_users.length > 0) {
                service.linked_users.forEach(user => {
                  userList.append(`
                <tr class="odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-black even:dark:bg-gray-900">
                  <td class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">${user.first_name} ${user.last_name}</td>
                  <td class="whitespace-nowrap font-sans text-white sm:py-3 sm:text-base md:py-2 lg:py-4">${user.email}</td>
                  <td class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">${user.pivot ? user.pivot.role : 'N/A'}</td>
                  <td class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">${user.pivot ? user.pivot.created_at : 'N/A'}</td>
                  <td class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
                      <button class="remove-user-btn w-full rounded-lg bg-white px-4 py-2 font-heading text-black transition duration-150 ease-in-out hover:text-yns_yellow" data-user-id="${user.id}" data-service-id="${service.id}">Remove</button>
                  </td>
                </tr>
              `);
                });
              } else {
                userList.append(
                  '<tr><td colspan="5" class="py-2 px-4 border-b text-center">No linked users found.</td></tr>'
                );
              }
            });
          } else {
            userList.append(
              '<tr><td colspan="5" class="py-2 px-4 border-b text-center">No users found.</td></tr>'
            );
          }
        },
        error: function(xhr) {
          console.error(xhr.responseJSON.message || 'Error fetching users.');
          showFailureNotification(xhr.responseJSON.message);
        }
      });
    }

    fetchUsers();

    jQuery('#user-list').on('click', '.remove-user-btn', function(e) {
      e.preventDefault();

      const userId = jQuery(this).data('user-id');
      const serviceId = jQuery(this).data('service-id');
      const url =
        '{{ route('admin.dashboard.delete-user', ['dashboardType' => '__dashboardType__', 'id' => '__userId__']) }}'
        .replace('__dashboardType__', dashboardType)
        .replace('__userId__', userId);
      console.log(url);

      showConfirmationNotification({
        text: "You are removing this user from this promotions company"
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: url,
            type: 'DELETE',
            data: {
              _token: '{{ csrf_token() }}',
              user_id: userId,
              service_id: serviceId,
            },
            success: function(response) {
              showSuccessNotification(response.message);
              location.reload(); // Reload the page to update the user list
            },
            error: function(xhr) {
              showFailureNotification(xhr.responseJSON.message);
            }
          });
        }
      });
    });
  });
</script>
