@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium font-heading text-sm text-ynsMedGray mb-2']) }}>
  {{ $value ?? $slot }}
</label>
