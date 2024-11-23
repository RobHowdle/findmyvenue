@props([
    'workingTimes' => null,
    'dashboardType',
    'user',
])

<form id="workingTimesForm" method="POST"
  action="{{ route('photographer.update', ['dashboardType' => $dashboardType, 'user' => $user->id]) }}" class="mt-8">
  @csrf
  @method('PUT')
  <div class="grid grid-cols-1 gap-4 md:grid-cols-1">
    @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
      <div class="flex items-center space-x-4">
        <div class="flex w-full items-center space-x-2" id="time_picker_{{ Str::slug($day) }}"
          @if (isset($workingTimes[$day]) && ($workingTimes[$day] == 'all-day' || $workingTimes[$day] == 'unavailable')) style="display:none" @endif>
          <x-time-input type="time" name="working_times[{{ Str::slug($day) }}][start]"
            value="{{ isset($workingTimes[$day]) && strpos($workingTimes[$day], '-') !== false ? explode('-', $workingTimes[$day])[0] : '' }}"
            class="rounded border-gray-300 text-white" step="900" />
          <span class="text-white">to</span>
          <x-time-input type="time" name="working_times[{{ Str::slug($day) }}][end]"
            value="{{ isset($workingTimes[$day]) && strpos($workingTimes[$day], '-') !== false ? explode('-', $workingTimes[$day])[1] : '' }}"
            class="rounded border-gray-300 text-white" step="900" />
        </div>

        <div class="flex w-full items-center space-x-2">
          <label for="all_day_{{ Str::slug($day) }}" class="text-white">{{ $day }}</label>
          <x-input-checkbox id="all_day_{{ Str::slug($day) }}" name="working_times[{{ Str::slug($day) }}]"
            value="all-day" :checked="isset($workingTimes[$day]) && $workingTimes[$day] == 'all-day'" class="text-white" />
          <span class="text-white">All Day</span>
          <x-input-checkbox id="unavailable_{{ Str::slug($day) }}" name="working_times[{{ Str::slug($day) }}]"
            value="unavailable" :checked="isset($workingTimes[$day]) && $workingTimes[$day] == 'unavailable'" class="text-white" />
          <span class="text-white">Unavailable</span>
        </div>

        @error('working_times.' . Str::slug($day) . '.start')
          <div class="text-sm text-red-500">{{ $message }}</div>
        @enderror
        @error('working_times.' . Str::slug($day) . '.end')
          <div class="text-sm text-red-500">{{ $message }}</div>
        @enderror
      </div>
    @endforeach
  </div>
  <button type="submit" class="mt-4 rounded bg-blue-500 px-4 py-2 text-white">Save</button>
</form>

<script>
  document.getElementById('workingTimesForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent the default form submission

    const form = this;
    const formData = new FormData(form);

    console.log(formData);

    // Ensure "All Day" and "Unavailable" checkboxes correctly reflect their respective time pickers' visibility
    // document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
    //   const day = checkbox.id.replace(/(all_day_|unavailable_)/, ''); // Extract day from checkbox ID
    //   const timePicker = document.getElementById('time_picker_' + day);

    //   // Ensure time picker exists before modifying its display
    //   if (timePicker) {
    //     // Ensure time picker is hidden when 'All Day' or 'Unavailable' is checked
    //     if (checkbox.checked && (checkbox.id.startsWith('all_day_') || checkbox.id.startsWith(
    //         'unavailable_'))) {
    //       timePicker.style.display = 'none'; // Hide time picker
    //     } else {
    //       timePicker.style.display = 'flex'; // Show time picker
    //     }
    //   }
    // });

    // Now submit the form
    fetch(form.action, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}', // Ensure CSRF token is sent
        },
        body: formData,
      })
      .then(response => response.text())
      .then(text => {
        try {
          const data = JSON.parse(text); // Try parsing as JSON
          if (data.success) {
            console.log('yes');
          } else {
            // Handle failure (show error message)
            console.log('no');
          }
        } catch (error) {
          console.error('Failed to parse response:', error);
          showFailureNotification('Error', 'An unexpected error occurred.');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showFailureNotification('Error', 'An unexpected error occurred.');
      });
  });

  // Toggle time pickers based on 'All Day' and 'Unavailable' checkbox state
  //   document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
  //     checkbox.addEventListener('change', function() {
  //       const day = this.id.replace(/(all_day_|unavailable_)/, ''); // Extract day from checkbox ID
  //       const timePicker = document.getElementById('time_picker_' + day);

  //       // Only proceed if timePicker element exists
  //       if (timePicker) {
  //         // Handle 'All Day' checkbox
  //         if (this.id.startsWith('all_day_')) {
  //           if (this.checked) {
  //             timePicker.style.display = 'none';
  //             document.getElementById('unavailable_' + day).checked =
  //               false; // Uncheck 'Unavailable' if checked
  //           } else {
  //             timePicker.style.display = 'flex';
  //           }
  //         }

  //         // Handle 'Unavailable' checkbox
  //         if (this.id.startsWith('unavailable_')) {
  //           if (this.checked) {
  //             timePicker.style.display = 'none';
  //             document.getElementById('all_day_' + day).checked = false; // Uncheck 'All Day' if checked
  //           } else {
  //             timePicker.style.display = 'flex';
  //           }
  //         }
  //       }
  //     });
  //   });
</script>
