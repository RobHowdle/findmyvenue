<x-app-layout>
  <x-slot name="header">
    <x-promoter-sub-nav :promoter="$promoter" :promoterId="$promoter->id" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <p class="text-center font-heading text-4xl font-bold">Add User to Promotion Company</p>
    <div class="flex justify-center">
      <input type="text" id="user-search" placeholder="Search for a user..." class="rounded border p-2" />
      <button id="search-button" class="ml-2 rounded bg-blue-500 p-2 text-white">Search</button>
    </div>

    <div id="search-results" class="mt-4"></div>
  </div>

</x-app-layout>
<script>
  $(document).ready(function() {
    $('#search-button').on('click', function() {
      const query = $('#user-search').val();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        url: "{{ route('admin.dashboard.promoter.search-users') }}",
        method: "GET",
        data: {
          query: query
        },
        success: function(data) {
          $('#search-results').html('');
          if (data.length) {
            data.forEach(user => {
              const userId = user.id;
              const userLinked = user.linked; // Ensure `linked` is returned in your response

              $('#search-results').append(`
                            <div class="border p-2 my-2 flex justify-between items-center">
                                <span>${user.name} (${user.email})</span>
                                ${userLinked
                                    ? `<button class="delete-user-button bg-red-500 text-white rounded p-1" data-user-id="${userId}">Delete</button>`
                                    : `<button class="add-user-button bg-green-500 text-white rounded p-1" data-user-id="${userId}">Add</button>`}
                            </div>
                        `);
            });
          } else {
            $('#search-results').append('<p>No users found.</p>');
          }
        },
        error: function(xhr) {
          $('#search-results').html('<p>Error retrieving users.</p>');
        }
      });
    });

    $(document).on('click', '.add-user-button', function() {
      const userId = $(this).data('user-id');
      const role = prompt('Enter a role for this user (e.g., owner, standard):');

      if (role) {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          url: "{{ route('admin.dashboard.promoter.add-user-to-company') }}",
          method: "POST",
          data: {
            user_id: userId,
            role: role,
            promoter_id: {{ $promoter->id }} // Ensure this is correctly referencing the promoter ID
          },
          success: function(response) {
            alert(response.message);
          },
          error: function(xhr) {
            alert('Error adding user: ' + xhr.responseJSON.message);
          }
        });
      }
    });

    $(document).on('click', '.delete-user-button', function() {
      const userId = $(this).data('user-id');

      if (confirm('Are you sure you want to delete this user from the promotion company?')) {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          url: "{{ route('admin.dashboard.promoter.delete-user') }}",
          method: "DELETE",
          data: {
            user_id: userId,
            promoter_id: {{ $promoter->id }} // Ensure this references the correct promoter ID
          },
          success: function(response) {
            alert(response.message);
            // Optionally, remove the user from the displayed list
            $(`[data-user-id="${userId}"]`).closest('div').remove();
          },
          error: function(xhr) {
            alert('Error deleting user: ' + xhr.responseJSON.message);
          }
        });
      }
    });
  });
</script>
