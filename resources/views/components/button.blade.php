@if (isset($href) && !empty($href))
  <a href="{{ $href }}" id="{{ $id }}"
    {{ $attributes->merge(['class' => 'rounded-lg bg-white px-4 py-2 text-black transition-all duration-300 ease-in-out hover:bg-gradient-to-t hover:from-yns_dark_orange hover:to-yns_yellow']) }}>
    {{ $label }}
  </a>
@else
  <span id="{{ $id }}"
    {{ $attributes->merge(['class' => 'cursor-pointer rounded-lg bg-white px-4 py-2 text-black transition-all duration-300 ease-in-out hover:bg-gradient-to-t hover:from-yns_dark_orange hover:to-yns_yellow']) }}>
    {{ $label }}
  </span>
@endif
