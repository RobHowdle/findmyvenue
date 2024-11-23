<x-app-layout :dashboardType="$dashboardType" :modules="$modules">
  <x-slot name="header">
    <x-sub-nav :userId="$userId" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div
        class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg border border-white bg-yns_dark_gray px-8 py-8 text-white">
        <div class="header mb-8">
          <div class="flex flex-row justify-between">
            <h1 class="font-heading text-4xl font-bold">My Jobs</h1>
            <a href="{{ route('admin.dashboard.jobs.create', ['dashboardType' => $dashboardType]) }}"
              class="rounded-lg bg-white px-4 py-2 text-black transition duration-300 hover:bg-gradient-to-t hover:from-yns_dark_orange hover:to-yns_yellow">New
              Job</a>
          </div>
        </div>
        <table class="w-full border border-white text-left font-sans rtl:text-right" id="jobs">
          <thead class="text-white underline dark:bg-black">
            <tr>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Client
              </th>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Job Type
              </th>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Amount
              </th>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Deadline
              </th>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Status
              </th>
              <th scope="col" class="md-text-2xl sm:px-2 sm:py-2 sm:text-xl md:px-6 md:py-3 lg:px-8 lg:py-4">Actions
              </th>
            </tr>
          </thead>
          <tbody>
            @forelse ($jobs as $job)
              <tr class="odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-black even:dark:bg-gray-900">
                <th scope="row"
                  class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
                  {{-- {{ $job->services->name }} --}}
                </th>
                <td class="rating-wrapper flex whitespace-nowrap sm:py-3 sm:text-base md:py-2 lg:py-4">
                  {{ $job->job_type }}
                </td>
                <td
                  class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
                  {{ $job->estimated_amount }}
                </td>
                <td class="whitespace-nowrap align-middle text-white sm:py-3 sm:text-base md:py-2 lg:py-4">
                  {{ $job->job_end_date }}
                </td>
                <td
                  class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
                  {{ $job->job_status }}
                </td>

                <td
                  class="whitespace-nowrap font-sans text-white sm:px-2 sm:py-3 sm:text-base md:px-6 md:py-2 md:text-lg lg:px-8 lg:py-4">
                  <button>View</button>
                  <button>Edit</button>
                  <button>Delete</button>
                </td>
              </tr>
            @empty
              <tr
                class="border-b odd:bg-white even:bg-gray-50 dark:border-gray-700 odd:dark:bg-black even:dark:bg-gray-900">
                <td colspan="6" class="text-center text-2xl text-white dark:bg-gray-900">No jobs found</td>
              </tr>
            @endforelse
          </tbody>
        </table>

      </div>
    </div>
  </div>
  </div>
</x-app-layout>
