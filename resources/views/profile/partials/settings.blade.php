<section>
  <header>
    <h2 class="font-heading text-xl font-medium text-white">
      {{ __('Settings') }}
    </h2>
    <p class="text-md mt-2 text-yns_light_gray"></p>
  </header>
  <div class="grid grid-cols-3 gap-4">
    @foreach ($modules as $moduleName => $moduleSettings)
      <div class="mb-4">
        <div class="card flex h-full flex-col">
          <div class="card-body flex-grow">
            <h5 class="card-title">{{ $moduleName }}</h5>
            <p class="card-text">{{ $moduleSettings['description'] }}</p>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="moduleSwitch{{ $loop->index }}"
                {{ $moduleSettings['is_enabled'] ? 'checked' : '' }}
                onchange="toggleModule('{{ $moduleName }}', this.checked, {{ $loop->index }})">
              <label class="form-check-label" for="moduleSwitch{{ $loop->index }}">
                <span class="slider"></span>
              </label>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</section>

<style>
  /* Styles for the switch toggle */
  .form-check-input {
    display: none;
  }

  .form-check-label {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
  }

  .form-check-label .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: background-color 0.4s;
    border-radius: 34px;
  }

  .form-check-input:checked+.slider {
    background-color: #4CAF50;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    border-radius: 50%;
    transition: transform 0.4s;
  }

  .form-check-input:checked+.slider:before {
    transform: translateX(26px);
  }

  .slider.active-slider {
    background-color: #4CAF50;
  }

  .slider.active-slider:before {
    transform: translateX(26px);
  }
</style>

<script>
  function toggleModule(moduleName, isEnabled, index) {
    const checkboxId = `moduleSwitch${index}`; // Construct the ID using index
    const checkbox = document.querySelector(`#${checkboxId}`);
    const slider = document.querySelector(`label[for="${checkboxId}"] .slider`);
    const dashboardType = "{{ $dashboardType }}"; // Correctly pass dashboardType

    console.log(moduleName + ' is now ' + (isEnabled ? 'Enabled' : 'Disabled'));

    // Check if checkbox was found
    if (!checkbox) {
      console.error('Checkbox not found for ID:', checkboxId);
      return; // Exit the function if checkbox not found
    }

    // Update the slider class
    if (slider) {
      slider.classList.toggle('active-slider', isEnabled);
    } else {
      console.error('Slider element not found for module:', moduleName);
    }

    fetch('{{ route('settings.updateModule', ['dashboardType' => $dashboardType]) }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
          module: moduleName,
          is_enabled: isEnabled,
          dashboardType: dashboardType,
        })
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        // Call the success notification function
        showSuccessNotification(data.message); // Adjust this line based on how your success message is returned
      })
      .catch(error => {
        // Call the failure notification function
        showFailureNotification('An error occurred: ' + error.message); // Customize the error message if needed
      });
  }

  function setInitialSliderStates() {
    const checkboxes = document.querySelectorAll('.form-check-input');

    checkboxes.forEach((checkbox) => {
      const slider = document.querySelector(`label[for="${checkbox.id}"] .slider`);
      if (slider) {
        slider.classList.toggle('active-slider', checkbox.checked);
      } else {
        console.error('Slider element not found for checkbox:', checkbox.id);
      }
    });
  }

  // Call the setInitialSliderStates function when the document is ready
  document.addEventListener('DOMContentLoaded', setInitialSliderStates);
</script>
