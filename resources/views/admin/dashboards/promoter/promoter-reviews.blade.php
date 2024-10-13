<x-app-layout>
  <x-slot name="header">
    <x-promoter-sub-nav :promoter="$promoter" :promoterId="$promoter->id" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div
        class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg border border-white bg-yns_dark_gray px-8 py-8 text-white">
        <div class="header border-b border-b-white">
          <h1 class="mb-8 font-heading text-4xl font-bold">Reviews</h1>
        </div>

        <table class="w-full border border-white text-left font-sans text-xl rtl:text-right" id="promoterReviewsTable">
          <thead class="border-b border-b-white text-xl text-white underline dark:bg-black">
            <tr>
              <th scope="col" class="px-6 py-4">
                Review
              </th>
              <th scope="col" class="px-6 py-4">
                Author
              </th>
              <th scope="col" class="px-8 py-4">
                Actions
              </th>
            </tr>
          </thead>
          <tbody>
            @if ($pendingReviews && count($pendingReviews) > 0)
              @foreach ($pendingReviews as $review)
                <tr class="odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-black even:dark:bg-gray-900">
                  <td scope="row" class="max-w-md whitespace-normal break-words px-6 py-4 font-sans text-white">
                    {{ $review->review }}
                  </td>

                  <td class="rating-wrapper whitespace-nowrap px-6 py-4">
                    {{ $review->author }}
                  </td>
                  <td class="max-w-md whitespace-normal break-words px-6 py-4 font-sans text-white">
                    <form class="mb-2"
                      action="{{ route('admin.promoter.dashboard.approve-display-review', $review->id) }}"
                      method="POST">
                      @csrf
                      <button type="submit"
                        class="display-review w-full rounded-lg bg-white px-4 py-2 font-heading text-black transition duration-150 ease-in-out hover:border-yns_yellow hover:bg-yns_teal hover:text-black">Display</button>
                    </form>
                    <form class="mb-2"
                      action="{{ route('admin.promoter.dashboard.approve-pending-review', $review->id) }}"
                      method="POST">
                      @csrf
                      <button type="submit"
                        class="approve-review w-full rounded-lg bg-white px-4 py-2 font-heading text-black transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">Approve</button>
                    </form>
                    <form class="mb-2" action="{{ route('admin.promoter.dashboard.delete-review', $review->id) }}"
                      method="POST">
                      @csrf
                      @method('DELETE')
                      <button
                        class="delete-review w-full rounded-lg bg-white px-4 py-2 font-heading text-black transition duration-150 ease-in-out hover:border-yns_yellow hover:bg-yns_red hover:text-black">Delete</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="3" class="text-center text-white">No Reviews</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
</x-app-layout>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('promoterReviewsTable');

    // Approve Display
    table.addEventListener('click', function(e) {
      if (e.target && e.target.matches('button.display-review')) {
        e.preventDefault();
        let form = e.target.closest('form');
        let url = form.action;

        fetch(url, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'Content-Type': 'application/json',
            },
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              showSuccessNotification(data.message)
              form.closest('tr').remove();
            } else {
              showFailureNotification(data.message)
            }
          });
      }
    });

    // Approve Review
    table.addEventListener('click', function(e) {
      if (e.target && e.target.matches('button.approve-review')) {
        e.preventDefault();
        let form = e.target.closest('form');
        let url = form.action;

        fetch(url, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'Content-Type': 'application/json',
            },
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              showSuccessNotification(data.message)
              form.closest('tr').remove();
            } else {
              showFailureNotification(data.message)
            }
          });
      }
    });

    // Delete Review
    table.addEventListener('click', function(e) {
      if (e.target && e.target.matches('button.delete-review')) {
        e.preventDefault();
        let form = e.target.closest('form');
        let url = form.action;

        fetch(url, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'Content-Type': 'application/json',
            },
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              showSuccessNotification(data.message)
              form.closest('tr').remove();
            } else {
              showFailureNotification(data.message)
            }
          });
      }
    });
  });
</script>
