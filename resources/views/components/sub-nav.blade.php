<div class="grid grid-cols-3 place-items-center text-white">
  @if ($userType === 'promoter')
    <div>Events YTD: {{ $eventsCountPromoterYtd }}</div>
    <div class="flex">Total Profit YTD:
      <div id="amountDisplay" class="ml-2">{{ formatCurrency($totalProfitsPromoterYtd) ?? '£0.00' }}</div>
    </div>
    <div class="rating-wrapper flex flex-row items-center gap-2">
      <p class="h-full place-content-end font-sans">Overall Rating:</p>
      <div class="ratings flex">
        {!! $overallRatingPromoter !!}
      </div>
    </div>
  @elseif ($userType === 'band')
    <div>Gigs YTD: {{ $gigsCountBandYtd }}</div>
    <div class="flex">Total Profit YTD:
      <div id="amountDisplay" class="ml-2">{{ formatCurrency($totalProfitsBandYtd) ?? '£0.00' }}</div>
    </div>
    <div class="rating-wrapper flex flex-row items-center gap-2">
      <p class="h-full place-content-end font-sans">Overall Rating:</p>
      <div class="ratings flex">
        {!! $overallRatingBand !!}
      </div>
    </div>
  @elseif ($userType === 'designer')
    <div>Total Projects: {{ $totalProjects }}</div>
    <div class="rating-wrapper flex flex-row items-center gap-2">
      <p class="h-full place-content-end font-sans">Overall Rating:</p>
      <div class="ratings flex">
        {!! $overallRatingDesigner !!}
      </div>
    </div>
  @elseif ($userType === 'venue')
    <div>Total Events Held: {{ $totalEventsHeld }}</div>
    <div class="rating-wrapper flex flex-row items-center gap-2">
      <p class="h-full place-content-end font-sans">Overall Rating:</p>
      <div class="ratings flex">
        {!! $overallRatingVenue !!}
      </div>
    </div>
  @else
    <div>Invalid user type</div>
  @endif
</div>