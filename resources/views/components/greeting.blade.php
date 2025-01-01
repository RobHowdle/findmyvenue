<h1 class="mb-2 font-heading text-xl font-bold text-white">{{ $greeting }}, {{ $userName }}</h1>
@if ($associatedEntity)
  <p class="mb-2 font-heading">Showing data for: {{ $associatedEntity }}</p>
@endif
