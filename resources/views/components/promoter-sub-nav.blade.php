<div class="grid grid-cols-3 place-items-center text-white">
  <div>Events YTD: {{ $eventsCountYTD }}</div>
  <div class="flex">Total Profit YTD:
    <div id="amountDisplay" class="ml-2">{{ formatCurrency($totalProfits) ?? '£0.00' }}</div>
  </div>
  <div class="rating-wrapper flex flex-row items-center gap-2">
    <p class="h-full place-content-end font-sans">Overall Rating:</p>
    <div class="ratings flex">
      {!! $overallReviews !!}
    </div>
  </div>
</div>
