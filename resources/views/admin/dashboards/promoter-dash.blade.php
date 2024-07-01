@role('promoter')
  <div
    class="collapsible-container mb-2 w-full max-w-7xl overflow-x-auto bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
    <div class="collapsible-header toggle-header p-6">
      <h2 class="text-xl text-gray-900 dark:text-gray-100">My Reviews</h2>
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
            <tr class="whitespace-nowrap border-b px-6 py-4 text-center">
              <th class="p-2">Author</th>
              <th class="p-2">Communication</th>
              <th class="p-2">Rate Of Pay</th>
              <th class="p-2">Promotion</th>
              <th class="p-2">Quality</th>
              <th class="p-2">Review</th>
              <th class="p-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($promoterReviews as $review)
              <tr>
                <td class="break-all p-2">{{ $review->author }}</td>
                <td class="break-all p-2">{{ $review->communication_rating }}</td>
                <td class="break-all p-2">{{ $review->rop_rating }}</td>
                <td class="break-all p-2">{{ $review->promotion_rating }}</td>
                <td class="break-all p-2">{{ $review->quality_rating }}</td>
                <td class="break-all p-2">{{ $review->review }}</td>
                @if ($review->review_approved == 0)
                  <td class="collapsible-actions flex items-center justify-center p-2">
                    <form action="{{ route('pending-review-promoter.approve-display', $review->id) }}" method="POST">
                      @csrf
                      <button type="submit" class="bg-yellow p-2">Approve & Display</button>
                    </form>
                    <form action="{{ route('pending-review-promoter.approve', $review->id) }}" method="POST">
                      @csrf
                      <button type="submit" class="bg-yellow p-2">Approve</button>
                    </form>
                  </td>
                @elseif($review->review_approved == 1 && $review->display == 0)
                  <td class="collapsible-actions flex items-center justify-center p-2">
                    <form action="{{ route('pending-review-promoter.display', $review->id) }}" method="POST">
                      @csrf
                      <button type="submit" class="bg-yellow p-2">Display</button>
                    </form>
                  </td>
                @elseif($review->review_approved == 1 && $review->display == 1)
                  <td class="collapsible-actions flex items-center justify-center p-2">
                    <form action="{{ route('pending-review-promoter.hide', $review->id) }}" method="POST">
                      @csrf
                      <button type="submit" class="bg-yellow p-2">Hide</button>
                    </form>
                  </td>
                @endif
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endrole
