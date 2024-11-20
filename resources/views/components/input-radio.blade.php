@props([
    'id' => null,
    'name' => null,
    'class' => null,
    'value' => null,
    'x-model' => null,
    'disabled' => false,
])

<input type="radio" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}"
  x-model="{{ $xModel }}" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
      'class' => "text-yns_cyan focus:ring-3 h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-yns_cyan dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-yns_red dark:focus:ring-yns_cyan dark:focus:ring-offset-yns_cyan $class",
  ]) !!} />
