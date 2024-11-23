@props(['id' => null, 'name' => null, 'disabled' => false, 'value' => null])

<input type="time" id="{{ $id }}" name="{{ $name }}" {{ $disabled ? 'disabled' : '' }}
  value="{{ $value }}" {!! $attributes->merge([
      'class' =>
          'border-yns_red w-full dark:border-yns_red dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm',
  ]) !!} />

<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[type="time"]').forEach(function(input) {
      input.addEventListener('change', function() {
        let value = input.value;
        if (value) {
          let timeParts = value.split(':');
          let minutes = parseInt(timeParts[1], 10);

          // Round minutes to nearest 15-minute interval
          if (minutes < 15) {
            timeParts[1] = '00';
          } else if (minutes < 30) {
            timeParts[1] = '15';
          } else if (minutes < 45) {
            timeParts[1] = '30';
          } else {
            timeParts[1] = '45';
          }

          // Update input value
          input.value = timeParts.join(':');
        }
      });
    });
  });
</script>
