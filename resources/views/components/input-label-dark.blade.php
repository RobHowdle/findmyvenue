@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium font-heading text-sm text-yns_med_gray mb-2']) }}>
  {{ $value ?? $slot }}
</label>
