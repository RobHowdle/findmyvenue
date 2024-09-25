@props(['id' => null, 'name' => null, 'disabled' => false])

<div class="flex items-center">
  <span
    class="inline-flex h-10 items-center rounded-l-md border border-r-0 border-ynsRed bg-gray-200 px-3 text-gray-700 dark:border-ynsRed dark:bg-gray-800 dark:text-gray-300">
    £
  </span>
  <input type="number" id="{{ $id }}" name="{{ $name }}" {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' =>
            'border-ynsRed w-full h-10 dark:border-ynsRed dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-r-md shadow-sm',
    ]) !!}>
</div>
