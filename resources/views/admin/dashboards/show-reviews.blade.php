<x-app-layout :dashboardType="$dashboardType" :modules="$modules">
  <x-slot name="header">
    <x-sub-nav :userId="$userId" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div
        class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg border border-white bg-yns_dark_gray px-8 py-8 text-white">
        <div class="header border-b border-b-white">
          <h1 class="mb-8 font-heading text-4xl font-bold">Reviews</h1>
          <div class="mb-4 flex justify-start space-x-4">
            <button id="all-reviews-btn" class="rounded-lg bg-yns_teal px-4 py-2 font-bold text-black">All
              Reviews</button>
            <button id="pending-reviews-btn" class="rounded-lg bg-yns_yellow px-4 py-2 font-bold text-black">Pending
              Reviews</button>
          </div>

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
          <tbody id="reviewsBody">
          </tbody>
        </table>
      </div>
    </div>
  </div>
</x-app-layout>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('promoterReviewsTable');
    const dashboardType = `{{ $dashboardType }}`;
    const filter = `{{ $filter }}`;

    function fetchReviews(dashboardType, filter) {
      let url = `/dashboard/${dashboardType}/filtered-reviews/${filter}`;
      fetch(url)
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => {
          populateReviewsTable(data.reviews);
        })
        .catch(error => {
          console.error('Error fetching reviews:', error);

        });
    }

    fetchReviews(dashboardType, filter);

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

    // Attach event listeners to buttons
    document.getElementById('pending-reviews-btn').addEventListener('click', function() {
      fetchReviews(dashboardType, 'pending');
    });

    document.getElementById('all-reviews-btn').addEventListener('click', function() {
      fetchReviews(dashboardType, 'all');
    });
  });

  function populateReviewsTable(reviews) {
    const tbody = document.getElementById('reviewsBody');
    const dashboardType = "{{ $dashboardType }}";

    const approveDisplayRoute = "{{ route('admin.dashboard.approve-display-review', [':dashboardType', ':id']) }}";
    const approveReviewRoute = "{{ route('admin.dashboard.approve-pending-review', [':dashboardType', ':id']) }}";
    const deleteReviewRoute = "{{ route('admin.dashboard.delete-review', [':dashboardType', ':id']) }}";
    const hideReviewRoute = "{{ route('admin.dashboard.hide-display-review', [':dashboardType', ':id']) }}";
    const unapproveReviewRoute = "{{ route('admin.dashboard.unapprove-review', [':dashboardType', ':id']) }}";

    tbody.innerHTML = '';

    if (reviews.length > 0) {
      reviews.forEach(review => {
        tbody.innerHTML += `
                <tr class=" border-gray-700 odd:dark:bg-black even:dark:bg-gray-900">
                    <td class="max-w-md whitespace-normal break-words px-6 py-4 font-sans text-white">${review.review}</td>
                    <td class="whitespace-nowrap px-6 py-4">${review.author}</td>
                    <td class="max-w-md whitespace-normal flex flex-row gap-4 break-words px-6 py-4 font-sans text-white">
                        <form class="mb-2" action="${review.review_approved == 1 ? unapproveReviewRoute.replace(':dashboardType', dashboardType).replace(':id', review.id) : approveDisplayRoute.replace(':dashboardType', dashboardType).replace(':id', review.id)}" method="POST">
                            <button type="submit" class="approve-review w-full rounded-lg bg-white px-4 py-2 font-heading text-black transition duration-150 ease-in-out ${review.review_approved == 1 ? 'hover:bg-yns_red' : 'hover:bg-yns_yellow'}">
                                ${review.review_approved == 1 ? '<span class="fas fa-times"></span>' : '<span class="fas fa-check"></span>'}
                            </button>
                        </form>

                        <form class="mb-2" action="${review.display == 1 ? hideReviewRoute.replace(':dashboardType', dashboardType).replace(':id', review.id) : approveDisplayRoute.replace(':dashboardType', dashboardType).replace(':id', review.id)}" method="POST">
                            <button type="submit" class="display-review w-full rounded-lg bg-white px-4 py-2 font-heading text-black transition duration-150 ease-in-out ${review.display == 1 ? 'hover:bg-yns_teal' : 'hover:bg-yns_red'}">
                                ${review.display == 1 ? '<span class="far fa-eye-slash"></span>' : '<span class="far fa-eye"></span>'}
                            </button>
                        </form>

                        <form class="mb-2" action="${deleteReviewRoute.replace(':dashboardType', dashboardType).replace(':id', review.id)}" method="POST">
                            <button class="delete-review w-full rounded-lg bg-white px-4 py-2 font-heading text-black transition duration-150 ease-in-out hover:bg-yns_red">
                                <span class="fas fa-trash-alt"></span>
                            </button>
                        </form>
                    </td>
                </tr>
            `;
      });
    } else {
      tbody.innerHTML = `
          <tr class=" border-gray-700 odd:dark:bg-black even:dark:bg-gray-900">
            <td colspan="3" class="whitespace-nowrap px-6 py-4 text-center">No Reviews</td>
          </tr>`;
    }
  }
</script>
