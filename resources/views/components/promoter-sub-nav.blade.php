    <div class="grid grid-cols-3 place-items-center text-white">
      <div>Events YTD: 30</div>
      <div class="flex">Total Proft YTD:
        <div id="amountDisplay" class="ml-2">{{ $totalProfits }}</div>
      </div>
      <div class="rating-wrapper flex flex-row items-center gap-2">Overall Rating:
        <div class="rating flex">
          {!! $overallReviews !!}
        </div>
      </div>
    </div>
    <script>
      window.amountValue = @json($totalProfits);
    </script>
