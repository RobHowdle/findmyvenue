<div class="grid grid-cols-3 place-items-center text-white">
  <div>Events YTD: 30</div>
  <div class="flex">Total Profit YTD:
    <div id="amountDisplay" class="ml-2">{{ formatCurrency($totalProfits) ?? '£0.00' }}</div>
  </div>
  <div class="rating-wrapper flex flex-row items-center gap-2">Overall Rating:
    <div class="rating flex">
      {!! $overallReviews !!}
    </div>
  </div>
</div>
