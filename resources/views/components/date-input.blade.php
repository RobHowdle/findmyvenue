@props(['id' => null, 'name' => null, 'disabled' => false])

<input type="date" id="{{ $id }}" name="{{ $name }}" {{ $disabled ? 'disabled' : '' }}
  {!! $attributes->merge([
      'class' =>
          'border-ynsRed w-full dark:border-ynsRed dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm',
  ]) !!} />
