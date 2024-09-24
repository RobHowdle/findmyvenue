<x-app-layout>
  <x-slot name="header">
    <x-promoter-sub-nav :promoterId="$promoter->id" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg bg-ynsDarkGray text-white">
        <div class="grid grid-cols-[1.25fr_1.75fr] border border-white">
          <div class="border-r border-r-white bg-opac8Black px-8 py-4">
            <div class="mb-8 flex flex-row justify-between">
              <button
                class="rounded-lg border bg-ynsLightGray px-4 py-2 font-bold text-black transition duration-150 ease-in-out hover:border-ynsYellow hover:text-ynsYellow">Export</button>
              <button
                class="rounded-lg border bg-ynsLightGray px-4 py-2 font-bold text-black transition duration-150 ease-in-out hover:border-ynsYellow hover:text-ynsYellow">New
                Budget</button>
            </div>

            <p class="mb-4 font-heading text-2xl font-bold">Incoming/ Outgoing/ Profit</p>
            <p class="font-heading text-xl text-ynsLightGray">Total Incoming: £3,500.00</p>
            <p class="mb-4 font-heading text-xl text-ynsLightGray">Total Outgoing: £280.00</p>
            <p class="font-heading text-2xl font-bold text-white">Total Profit: £3,220.00</p>
          </div>
          <div>
            <p class="px-8 py-4 font-heading text-4xl font-bold">Finances</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
