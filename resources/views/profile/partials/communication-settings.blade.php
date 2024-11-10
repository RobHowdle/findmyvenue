<section>
  <header>
    <h2 class="font-heading text-xl font-medium text-white">
      {{ __('Communication Settings') }}
    </h2>
    <p class="text-md mt-2 text-yns_light_gray">Control how/ if we can contact you.</p>
    <p class="text-md mt-2 text-yns_light_gray"><span class="font-bold">PLEASE NOTE: </span>We require you to be able to
      be contacted for system alerts and legal/ policy updates.</p>
  </header>
  <div class="grid grid-cols-3 gap-4">
    @foreach (config('mailing_preferences.communication_preferences') as $preferenceKey => $preference)
      <div class="mb-4">
        <div class="card flex h-full flex-col">
          <div class="card-body flex-grow">
            <h5 class="card-title">{{ ucfirst(str_replace('_', ' ', $preferenceKey)) }}</h5>
            <p class="card-text">{{ $preference['description'] }}</p>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="preferenceSwitch{{ $preferenceKey }}"
                {{ isset($communications[$preferenceKey]) && $communications[$preferenceKey] ? 'checked' : '' }}
                onchange="togglePreferences('{{ $preferenceKey }}', this.checked)"
                @if (in_array($preferenceKey, ['system_announcements', 'legal_or_policy_updates'])) disabled @endif>
              <label
                class="form-check-label {{ in_array($preferenceKey, ['system_announcements', 'legal_or_policy_updates']) ? 'disabled-slider' : '' }}"
                for="preferenceSwitch{{ $preferenceKey }}">
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

  /* Style for the disabled slider when it's in an active state */
  .disabled-slider .slider.active-slider {
    background-color: #ccc !important;
    /* Change color for the disabled state */
    cursor: not-allowed;
    /* Show that the slider is not clickable */
  }

  /* Add this rule to ensure the slider is visually inactive when disabled */
  .disabled-slider .slider:before {
    /* background-color: #ccc !important; */
    /* Change the slider's circle color when disabled */
    transform: none !important;
    /* Prevent it from moving */
    box-shadow: none !important;
    /* Remove any shadow to indicate it's disabled */
  }

  /* Optional: Make the whole slider visually look inactive (grayed-out) */
  .disabled-slider {
    opacity: 0.6;
    /* Decrease opacity to indicate it's disabled */
    pointer-events: none;
    /* Prevent interaction with the slider */
  }
</style>


<script>
  function togglePreferences(preferenceKey, isEnabled) {
    const preferences = {}; // Object to store the updated preference
    preferences[preferenceKey] = isEnabled; // Set the updated preference to true/false

    fetch('{{ route('communications.updatePreferences', ['dashboardType' => $dashboardType]) }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(preferences) // Send preferences as a JSON request body
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        const slider = document.querySelector(`label[for="preferenceSwitch${preferenceKey}"] .slider`);
        if (slider) {
          slider.classList.toggle('active-slider', isEnabled);
        }
        showSuccessNotification(data.message); // Show success notification
      })
      .catch(error => {
        showFailureNotification('An error occurred: ' + error.message); // Show failure notification
      });
  }



  document.addEventListener('DOMContentLoaded', () => {
    setInitialSliderStates(); // Call this function to initialize the state

    const checkboxes = document.querySelectorAll('.form-check-input');
  });


  document.addEventListener('DOMContentLoaded', () => {
    setInitialSliderStates(); // Call this function to initialize the state

    const checkboxes = document.querySelectorAll('.form-check-input');
  });


  function setInitialSliderStates() {
    const checkboxes = document.querySelectorAll('.form-check-input');

    checkboxes.forEach((checkbox) => {
      const slider = document.querySelector(`label[for="${checkbox.id}"] .slider`);
      if (slider) {
        slider.classList.toggle('active-slider', checkbox.checked);
      }
    });
  }
  // Call the setInitialSliderStates function when the document is ready
  document.addEventListener('DOMContentLoaded', setInitialSliderStates);
</script>
