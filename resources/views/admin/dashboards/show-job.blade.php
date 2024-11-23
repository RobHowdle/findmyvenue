<x-app-layout :dashboardType="$dashboardType" :modules="$modules">
  <x-slot name="header">
    <x-sub-nav :userId="$userId" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg border border-white bg-yns_dark_gray text-white">
        <div class="header border-b border-b-white px-8 pt-8">
          <div class="flex flex-row justify-between">
            <h1 class="font-heading text-4xl font-bold">Job {{ $job->id }} - {{ $job->name }}</h1>
            {{-- <a href="{{ route('admin.dashboard.jobs.create', ['dashboardType' => $dashboardType]) }}"
              class="rounded-lg bg-white px-4 py-2 text-black transition duration-300 hover:bg-gradient-to-t hover:from-yns_dark_orange hover:to-yns_yellow">New
              Job</a> --}}
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</x-app-layout>
