@role('administrator')
  <div class="collapsible-container mb-2 overflow-x-auto bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
    <div class="collapsible-header toggle-header p-6">
      <h2 class="text-xl text-gray-900 dark:text-gray-100">Users</h2>
      <svg class="toggle-icon h-6 w-6 fill-current text-gray-600 transition-transform" xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20">
        <path class="chevron-down" fill-rule="evenodd" clip-rule="evenodd"
          d="M10 13.59l-5.3-5.3a1 1 0 011.4-1.42L10 11.76l4.9-4.89a1 1 0 111.4 1.42l-5.3 5.3a1 1 0 01-1.4 0z" />
      </svg>
    </div>
    <div class="collapsible-content">
      <div class="p-6 text-xl text-gray-900 dark:text-gray-100">
        <table class="w-full table-auto border border-gray-100 text-left dark:bg-gray-800">
          <thead>
            <tr class="whitespace-nowrap border-b px-6 py-4">
              <th class="p-2">User</th>
              <th class="p-2">Role</th>
              <th class="p-2">Email</th>
              <th class="p-2">Last Logged In</th>
              <th class="p-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($users as $user)
              <tr>
                <td class="p-2">{{ $user->name }}</td>
                <td class="p-2">
                  @foreach ($user->roles as $role)
                    {{ $role->name }}
                  @endforeach
                </td>
                <td class="p-2">{{ $user->email }}</td>
                <td class="p-2">
                  @if ($user->last_logged_in)
                    {{ $user->last_logged_in->format('d/m/Y') }} ({{ $user->last_logged_in->diffForHumans() }})
                  @else
                    Never logged in
                  @endif
                </td>
                <td class="collapsible-actions flex p-2">
                  <form action="{{ route('profile.edit', $user->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-yellow p-2">Edit</button>
                  </form>
                  <button class="bg-red p-2">Delete</button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endrole

{{-- Pending Promoter Reviews --}}
@role('administrator')
  <div class="collapsible-container mb-2 overflow-x-auto bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
    <div class="collapsible-header toggle-header p-6">
      <h2 class="text-xl text-gray-900 dark:text-gray-100">Pending Promoter Reviews</h2>
      <svg class="toggle-icon h-6 w-6 fill-current text-gray-600 transition-transform" xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20">
        <path class="chevron-down" fill-rule="evenodd" clip-rule="evenodd"
          d="M10 13.59l-5.3-5.3a1 1 0 011.4-1.42L10 11.76l4.9-4.89a1 1 0 111.4 1.42l-5.3 5.3a1 1 0 01-1.4 0z" />
      </svg>
    </div>
    <div class="collapsible-content">
      <div class="p-6 text-xl text-gray-900 dark:text-gray-100">

        <table class="w-full table-auto border border-gray-100 text-left dark:bg-gray-800">
          <thead>
            <tr class="whitespace-nowrap border-b px-6 py-4">
              <th class="p-2">Promoter</th>
              <th class="p-2">Review</th>
              <th class="p-2">Author</th>
              <th class="p-2">IP</th>
              <th class="p-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($pendingPromoterReviews as $review)
              <tr>
                <td class="p-2">{{ $review->promoter->name }}</td>
                <td class="p-2">{{ $review->review }}</td>
                <td class="p-2">{{ $review->author }}</td>
                <td class="p-2">{{ $review->reviwer_ip }}</td>
                <td class="collapsible-actions flex items-center p-2">
                  <form action="{{ route('pending-review-promoter.approve-display', $review->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-yellow p-2">Approve & Display</button>
                  </form>
                  <form action="{{ route('pending-review-promoter.approve', $review->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-yellow p-2">Approve</button>
                  </form>
                  <button class="bg-red p-2">Delete</button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endrole

{{-- Pending Venue Reviews --}}
@role('administrator')
  <div class="collapsible-container mb-2 overflow-x-auto bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
    <div class="collapsible-header toggle-header p-6">
      <h2 class="text-xl text-gray-900 dark:text-gray-100">Pending Venue Reviews</h2>
      <svg class="toggle-icon h-6 w-6 fill-current text-gray-600 transition-transform" xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20">
        <path class="chevron-down" fill-rule="evenodd" clip-rule="evenodd"
          d="M10 13.59l-5.3-5.3a1 1 0 011.4-1.42L10 11.76l4.9-4.89a1 1 0 111.4 1.42l-5.3 5.3a1 1 0 01-1.4 0z" />
      </svg>
    </div>
    <div class="collapsible-content">
      <div class="p-6 text-xl text-gray-900 dark:text-gray-100">
        <table class="w-full table-auto border border-gray-100 text-left dark:bg-gray-800">
          <thead>
            <tr class="whitespace-nowrap border-b px-6 py-4">
              <th class="p-2">Venue</th>
              <th class="p-2">Review</th>
              <th class="p-2">Author</th>
              <th class="p-2">IP</th>
              <th class="p-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($pendingVenueReviews as $review)
              <tr>
                <td class="p-2">{{ $review->venue->name }}</td>
                <td class="p-2">{{ $review->review }}</td>
                <td class="p-2">{{ $review->author }}</td>
                <td class="p-2">{{ $review->reviwer_ip }}</td>
                <td class="collapsible-actions flex items-center p-2">
                  <form action="{{ route('pending-review-venue.approve-display', $review->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-yellow p-2">Approve & Display</button>
                  </form>
                  <form action="{{ route('pending-review-venue.approve', $review->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-yellow p-2">Approve</button>
                  </form>
                  <button class="bg-red p-2">Delete</button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endrole

{{-- User Service Linking --}}
@role('administrator')
  <div class="collapsible-container mb-2 overflow-x-auto bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
    <div class="collapsible-header toggle-header p-6">
      <h2 class="text-xl text-gray-900 dark:text-gray-100">User Service Linking</h2>
      <svg class="toggle-icon h-6 w-6 fill-current text-gray-600 transition-transform" xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20">
        <path class="chevron-down" fill-rule="evenodd" clip-rule="evenodd"
          d="M10 13.59l-5.3-5.3a1 1 0 011.4-1.42L10 11.76l4.9-4.89a1 1 0 111.4 1.42l-5.3 5.3a1 1 0 01-1.4 0z" />
      </svg>
    </div>
    <div class="collapsible-content">
      <div class="p-6 text-xl text-gray-900 dark:text-gray-100">
        <form class="flex flex-row gap-4" action="{{ route('user-service-link') }}" method="POST">
          @csrf
          <select name="user_select"
            class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500">
            @foreach ($users as $user)
              <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
          </select>

          <select id="type-select" name="type-select"
            class="peer block w-full appearance-none border-0 border-b-2 border-gray-300 bg-transparent px-0 py-2.5 text-sm text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-0 dark:border-gray-600 dark:text-white dark:focus:border-blue-500">
            <option value="">Please Select</option>
            <option value="venues">Venue</option>
            <option value="promoters">Promoter</option>
            <option value="other_service">Other Service</option>
          </select>

          <div id="dynamic-select-container" name="service_select_id" class="w-full"></div>

          <button type="submit">Link</button>
        </form>
      </div>
    </div>
  </div>
@endrole
