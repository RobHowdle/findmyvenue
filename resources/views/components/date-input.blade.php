@props(['id' => null, 'name' => null, 'disabled' => false])

<input type="date" id="{{ $id }}" name="{{ $name }}" {{ $disabled ? 'disabled' : '' }}
  {!! $attributes->merge([
      'class' =>
          'border-yns_red w-full dark:border-yns_red dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm',
  ]) !!} />
