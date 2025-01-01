<x-guest-layout>
  <div class="mx-auto min-h-screen w-full max-w-xl pt-44">
    <p class="px-8 py-8 text-center font-heading text-4xl font-bold text-white">Register</p>
    <div class="rounded bg-black p-8 font-sans">
      <p class="mb-2 text-white">
        We take security <span class="font-bold">very</span> seriously and are committed to protecting you and your data.
        Please ensure
        that all of your information is accurate and secure.
      </p>

      <form id="registration-form" method="POST" action="{{ route('register') }}">
        @csrf
        <div>
          <x-input-label for="first_name" :value="__('First Name')" />
          <x-text-input id="first_name" class="mt-1 block w-full" name="first_name" :value="old('first_name')" required autofocus
            autocomplete="first_name" />
          <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <div class="mt-4">
          <x-input-label for="last_name" :value="__('Last Name')" />
          <x-text-input id="last_name" class="mt-1 block w-full" name="last_name" :value="old('last_name')" required autofocus
            autocomplete="last_name" />
          <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <div class="mt-4">
          <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
          <x-date-input id="date_of_birth" class="mt-1 block w-full" name="date_of_birth" :value="old('date_of_birth')" required
            autofocus autocomplete="date_of_birth" />
          <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
        </div>

        <div class="mt-4">
          <x-input-label for="email" :value="__('Email')" />
          <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')"
            required autocomplete="email" />
          <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
          <x-input-label for="password" :value="__('Password')" />
          <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required
            autocomplete="new-password" />
          <div id="password-strength-container"
            style="width: 100%; height: 10px; background-color: #e0e0e0; border-radius: 5px; margin-top: 5px;">
            <div id="password-strength-meter" style="height: 100%; width: 0%; border-radius: 5px;"></div>
          </div>
          <span id="password-strength-text"></span>
          <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div id="password-requirements" class="mt-2 w-full text-sm text-white">
          <p class="font-bold">Password Requirements:</p>
          <ul>
            <li class="flex items-center">
              <span id="length-requirement" class="requirement flex items-center">
                <svg id="length-icon" class="mr-2 hidden h-4 w-4 text-green-400" fill="currentColor"
                  viewBox="0 0 20 20">
                  <path d="M6 10l2 2 6-6-1.5-1.5L8 10.5l-3.5-3.5L3 8l3 3z" />
                </svg>
                Minimum 8 characters
              </span>
            </li>
            <li class="flex items-center">
              <span id="uppercase-requirement" class="requirement flex items-center">
                <svg id="uppercase-icon" class="mr-2 hidden h-4 w-4 text-green-400" fill="currentColor"
                  viewBox="0 0 20 20">
                  <path d="M6 10l2 2 6-6-1.5-1.5L8 10.5l-3.5-3.5L3 8l3 3z" />
                </svg>
                At least 1 uppercase letter (A-Z)
              </span>
            </li>
            <li class="flex items-center">
              <span id="lowercase-requirement" class="requirement flex items-center">
                <svg id="lowercase-icon" class="mr-2 hidden h-4 w-4 text-green-400" fill="currentColor"
                  viewBox="0 0 20 20">
                  <path d="M6 10l2 2 6-6-1.5-1.5L8 10.5l-3.5-3.5L3 8l3 3z" />
                </svg>
                At least 1 lowercase letter (a-z)
              </span>
            </li>
            <li class="flex items-center">
              <span id="number-requirement" class="requirement flex items-center">
                <svg id="number-icon" class="mr-2 hidden h-4 w-4 text-green-400" fill="currentColor"
                  viewBox="0 0 20 20">
                  <path d="M6 10l2 2 6-6-1.5-1.5L8 10.5l-3.5-3.5L3 8l3 3z" />
                </svg>
                At least 1 number (0-9)
              </span>
            </li>
            <li class="flex items-center">
              <span id="special-requirement" class="requirement flex items-center">
                <svg id="special-icon" class="mr-2 hidden h-4 w-4 text-green-400" fill="currentColor"
                  viewBox="0 0 20 20">
                  <path d="M6 10l2 2 6-6-1.5-1.5L8 10.5l-3.5-3.5L3 8l3 3z" />
                </svg>
                At least 1 special character (@$!%*?&)
              </span>
            </li>
            <li class="flex items-center">
              <span id="not-compromised-requirement" class="requirement flex items-center">
                <svg id="not-compromised-icon" class="mr-2 hidden h-4 w-4 text-green-400" fill="currentColor"
                  viewBox="0 0 20 20">
                  <path d="M6 10l2 2 6-6-1.5-1.5L8 10.5l-3.5-3.5L3 8l3 3z" />
                </svg>
                Must not be compromised
              </span>
            </li>
          </ul>
        </div>

        <div class="mt-4">
          <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
          <x-text-input id="password_confirmation" class="mt-1 block w-full" type="password"
            name="password_confirmation" required autocomplete="new-password" />
          <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-4">
          <x-input-label for="role" :value="__('Select User Role')" />
          <select id="role" name="role"
            class="mt-1 block w-full rounded-md border-yns_red shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600"
            required autofocus autocomplete="role">
            @foreach ($roles as $role)
              <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
            @endforeach
          </select>
          <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="mt-4 flex items-center justify-end">
          <a class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
            href="{{ route('login') }}">
            {{ __('Already registered?') }}
          </a>

          <x-primary-button id="register-button"
            class="ms-4 bg-gradient-to-t from-yns_dark_orange to-yns_yellow text-white">
            {{ __('Register') }}
          </x-primary-button>
        </div>
      </form>
    </div>
  </div>
</x-guest-layout>

<script>
  // Force the Date Picker to only use the date for DOB
  document.addEventListener('DOMContentLoaded', function() {
    const datetimeInput = document.getElementById('date_of_birth');
    const dateValue = datetimeInput.value.split('T')[0];
    datetimeInput.type = 'date';
    datetimeInput.value = dateValue;
  });

  jQuery(document).ready(function() {
    jQuery('#registration-form').on('submit', function(event) {
      event.preventDefault(); // Prevent the default form submission

      const formData = jQuery(this).serialize(); // Serialize the form data
      $.ajax({
        url: jQuery(this).attr('action'), // Use the form's action attribute
        type: 'POST',
        dataType: 'json', // Ensure that the response is expected as JSON
        headers: {
          'Accept': 'application/json', // Tell the server to send a JSON response
        },
        data: formData,
        success: function(response) {
          console.log(response); // Log the entire response for debugging
          if (response.success) {
            showSuccessNotification(response.message); // Ensure message is defined
            setTimeout(() => {
              window.location.href = response.redirect;
            }, 3000);
          } else {
            showSuccessNotification('Registration successful!'); // Fallback message
            setTimeout(() => {
              window.location.href = response.redirect;
            }, 3000);
          }
        },
        error: function(xhr) {
          if (xhr.responseJSON && xhr.responseJSON.errors) {
            const errors = xhr.responseJSON.errors;
            console.log(errors); // Log errors for debugging

            if (errors.password && errors.password.includes(
                'This password has been compromised in a data breach. Please choose a different password.'
              )) {
              showWarningNotification(errors.password[0]);
            } else if (xhr.status === 422) {
              // Handle validation errors
              for (const error in errors) {
                if (errors.hasOwnProperty(error)) {
                  showFailureNotification(errors[error][0]); // Show the first error message
                }
              }
            } else {
              showFailureNotification('Registration failed. Please try again.');
            }
          } else {
            showFailureNotification('An unknown error occurred.');
          }
        }
      });
    });

    // window.showSuccessNotification = function(message) {
    //   Swal.fire({
    //     showConfirmButton: false,
    //     toast: true,
    //     position: "top-end",
    //     timer: 3000,
    //     timerProgressBar: true,
    //     customClass: {
    //       popup: "bg-yns_dark_gray !important rounded-lg font-heading",
    //       title: "text-black",
    //       html: "text-black",
    //     },
    //     icon: "success",
    //     title: "Success!",
    //     text: message,
    //   });
    // };

    // window.showFailureNotification = function(message) {
    //   Swal.fire({
    //     showConfirmButton: false,
    //     toast: true,
    //     position: "top-end",
    //     timer: 3000,
    //     timerProgressBar: true,
    //     customClass: {
    //       popup: "bg-yns_dark_gray !important rounded-lg font-heading",
    //       title: "text-black",
    //       html: "text-black",
    //     },
    //     icon: "error",
    //     title: "Oops!",
    //     text: message,
    //   });
    // };

    // window.showWarningNotification = function(message) {
    //   Swal.fire({
    //     showConfirmButton: true,
    //     toast: false,
    //     customClass: {
    //       popup: "bg-yns_dark_gray !important rounded-lg font-heading",
    //       title: "text-yns_red",
    //       html: "text-white",
    //     },
    //     icon: "warning",
    //     title: "Warning!",
    //     text: message,
    //   });
    // };

    // window.showConfirmationNotification = function(options) {
    //   return Swal.fire({
    //     showConfirmButton: true,
    //     confirmButtonText: "I understand",
    //     showCancelButton: true,
    //     toast: false,
    //     customClass: {
    //       popup: "bg-yns_dark_gray !important rounded-lg font-heading",
    //       title: "text-white",
    //       text: "text-white !important",
    //     },
    //     icon: "warning",
    //     title: "Are you sure?",
    //     text: options.text,
    //   });
    // };

    // Other JavaScript code for password strength and requirements can stay the same
    document.addEventListener('DOMContentLoaded', function() {
      const datetimeInput = document.getElementById('date_of_birth');
      const dateValue = datetimeInput.value.split('T')[0];
      datetimeInput.type = 'date';
      datetimeInput.value = dateValue;
    });

    document.getElementById('password').addEventListener('input', function() {
      const password = this.value;
      const strengthMeter = document.getElementById('password-strength-meter');
      const strengthText = document.getElementById('password-strength-text');
      let strength = 0;

      // Check password strength
      if (password.length >= 8) {
        strength++;
      }
      if (/[A-Z]/.test(password)) {
        strength++;
      }
      if (/[0-9]/.test(password)) {
        strength++;
      }
      if (/[@$!%*?&]/.test(password)) {
        strength++;
      }

      // Update meter and text based on strength
      switch (strength) {
        case 0:
          strengthMeter.style.width = '0%';
          strengthMeter.className = ''; // No class
          strengthText.textContent = '';
          break;
        case 1:
          strengthMeter.style.width = '25%';
          strengthMeter.className = 'weak';
          strengthText.textContent = 'Weak';
          break;
        case 2:
          strengthMeter.style.width = '50%';
          strengthMeter.className = 'medium';
          strengthText.textContent = 'Medium';
          break;
        case 3:
          strengthMeter.style.width = '75%';
          strengthMeter.className = 'strong';
          strengthText.textContent = 'Strong';
          break;
        case 4:
          strengthMeter.style.width = '100%';
          strengthMeter.className = 'strong';
          strengthText.textContent = 'Very Strong';
          break;
        default:
          strengthMeter.style.width = '0%';
          strengthText.textContent = '';
          break;
      }
    });

    document.getElementById('password').addEventListener('input', function() {
      const password = this.value;

      // Requirement elements
      const lengthRequirement = document.getElementById('length-requirement');
      const uppercaseRequirement = document.getElementById('uppercase-requirement');
      const lowercaseRequirement = document.getElementById('lowercase-requirement');
      const numberRequirement = document.getElementById('number-requirement');
      const specialRequirement = document.getElementById('special-requirement');
      const notCompromisedRequirement = document.getElementById('not-compromised-requirement');

      // Check password requirements
      lengthRequirement.classList.toggle('valid', password.length >= 8);
      document.getElementById('length-icon').classList.toggle('hidden', password.length < 8);

      uppercaseRequirement.classList.toggle('valid', /[A-Z]/.test(password));
      document.getElementById('uppercase-icon').classList.toggle('hidden', !/[A-Z]/.test(password));

      lowercaseRequirement.classList.toggle('valid', /[a-z]/.test(password));
      document.getElementById('lowercase-icon').classList.toggle('hidden', !/[a-z]/.test(password));

      numberRequirement.classList.toggle('valid', /[0-9]/.test(password));
      document.getElementById('number-icon').classList.toggle('hidden', !/[0-9]/.test(password));

      specialRequirement.classList.toggle('valid', /[@$!%*?&]/.test(password));
      document.getElementById('special-icon').classList.toggle('hidden', !/[@$!%*?&]/.test(password));

      // Assuming you have a function to check if the password is compromised
      const isCompromised = false; // Replace with actual compromised password check logic
      notCompromisedRequirement.classList.toggle('valid', !isCompromised);
      document.getElementById('not-compromised-icon').classList.toggle('hidden', isCompromised);
    });
  });
</script>
