<x-app-layout>
  <x-slot name="header">
    <x-sub-nav :userId="$userId" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg border border-white bg-yns_dark_gray text-white">
        <div class="header px-8 pt-8">
          <h1 class="mb-8 font-heading text-4xl font-bold">New Event</h1>
        </div>
        <form id="eventForm" action="{{ route('admin.dashboard.store-new-event', ['dashboardType' => $dashboardType]) }}"
          method="POST" enctype="multipart/form-data">
          @csrf
          <div class="grid grid-cols-3 gap-x-8 px-8 py-8">
            <div class="col">
              <input type="hidden" id="dashboard_type" value="{{ $dashboardType }}">
              <div class="group mb-4">
                <x-input-label-dark>Event Name</x-input-label-dark>
                <x-text-input id="event_name" name="event_name"></x-text-input>
                @error('event_name')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
              </div>
              <div class="group mb-4">
                <x-input-label-dark>Date & Time of Event</x-input-label-dark>
                <x-date-input id="merged_date_time" name="merged_date_time"></x-date-input>
                @error('merged_date_time')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
              </div>

              <div class="group mb-4 hidden">
                <x-input-label-dark>Date of Event</x-input-label-dark>
                <span>This is supposed to be hidden...naughty naughty</span>
                <x-date-input id="event_date" name="event_date"></x-date-input>
                @error('event_date')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
              </div>

              <div class="group mb-4 hidden">
                <x-input-label-dark>Start Time</x-input-label-dark>
                <span>This is supposed to be hidden...naughty naughty</span>
                <x-text-input class="w-auto" id="event_start_time" name="event_start_time"></x-text-input>
                @error('event_start_time')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
              </div>
              @if ($dashboardType === 'promoter')
                <div class="group mb-4 hidden">
                  <x-input-label-dark>Promoter</x-input-label-dark>
                  <span>This is supposed to be hidden...naughty naughty</span>
                  <x-text-input class="w-auto" id="promoter_id" name="promoter_id"
                    value="{{ $role->id }}"></x-text-input>
                  @error('promoter_id')
                    <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                  @enderror
                </div>
              @else
                <div class="group mb-4">
                  <x-input-label-dark>Promoter</x-input-label-dark>
                  <x-text-input id="promoter_name" name="promoter_name"
                    placeholder="Search for a promoter..."></x-text-input>
                  <input type="hidden" id="promoter_id" name="promoter_id" value="">
                  <ul id="promoter-suggestions"
                    class="absolute z-10 mt-1 hidden rounded-md border border-gray-300 bg-white shadow-lg">
                  </ul>
                  @error('promoter_id')
                    <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                  @enderror
                </div>
              @endif

              <div class="group mb-4">
                <x-input-label-dark>End Time</x-input-label-dark>
                <x-text-input class="w-auto" id="event_end_time" name="event_end_time"></x-text-input>
                @error('event_end_time')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
              </div>

              <div class="group mb-4">
                <x-input-label-dark>Description</x-input-label-dark>
                <x-textarea-input id="event_description" name="event_description" class="w-full"></x-textarea-input>
                @error('event_description')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
              </div>

              <div class="group mb-4">
                <x-input-label-dark>Facebook Event Link</x-input-label-dark>
                <x-text-input id="facebook_event_url" name="facebook_event_url"></x-text-input>
                @error('facebook_event_url')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
              </div>
              <div class="group mb-4">
                <x-input-label-dark>Pre Sale Ticket Link</x-input-label-dark>
                <x-text-input id="ticket_url" name="ticket_url"></x-text-input>
                @error('ticket_url')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
              </div>
              <div class="group mb-4">
                <x-input-label-dark>Door Ticket Price</x-input-label-dark>
                <x-number-input-pound id="otd_ticket_price" name="otd_ticket_price"></x-number-input-pound>
                @error('otd_ticket_price')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
              </div>
            </div>
            <div class="col">
              <div class="group">
                <x-input-label-dark>Poster</x-input-label-dark>
                <x-input-file id="poster_url" name="poster_url"></x-input-file>
                <div class="mt-4">
                  <img id="posterPreview" src="#" alt="Poster Preview" class="hidden h-auto w-400">
                </div>
                @error('poster_url')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
              </div>
            </div>
            <div class="col">
              <div class="group mb-4">
                <x-input-label-dark>Venue</x-input-label-dark>
                <x-text-input id="venue_name" name="venue_name" autocomplete="off"></x-text-input>
                <ul id="venue-suggestions"
                  class="max-h-60 absolute z-10 hidden overflow-auto border border-gray-300 bg-white">
                </ul>
                <x-text-input id="venue_id" name="venue_id" class="hidden"></x-text-input>
                @error('venue_name')
                  <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                @enderror
              </div>
              <div class="group" id="band-rows-container">
                <div class="group mb-4">
                  <x-input-label-dark>Headline Band</x-input-label-dark>
                  <x-text-input id="headliner-search" name="headliner" autocomplete="off"></x-text-input>
                  <ul id="headliner-suggestions"
                    class="max-h-60 absolute z-10 hidden overflow-auto border border-gray-300 bg-white"></ul>
                  <x-text-input id="headliner_id" name="headliner_id" class="hidden"></x-text-input>
                  </ul>
                  @error('headliner')
                    <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                  @enderror
                </div>
                <div class="group mb-4">
                  <x-input-label-dark>Main Support</x-input-label-dark>
                  <x-text-input id="mainSupport-search" name="mainSupport" autocomplete="off"></x-text-input>
                  <ul id="mainSupport-suggestions"
                    class="max-h-60 absolute z-10 hidden overflow-auto border border-gray-300 bg-white"></ul>
                  <x-text-input id="main_support_id" name="main_support_id" class="hidden"></x-text-input>
                  @error('mainSupport')
                    <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                  @enderror
                </div>
                <div class="group mb-4" id="bandsContainer">
                  <div class="band-input-row mt-4 flex">
                    <div class="flex flex-row">
                      <div class="group w-full">
                        <x-input-label-dark>Band</x-input-label-dark>
                        <x-text-input id="band_1" name="band[]" class="band-input"
                          autocomplete="off"></x-text-input>
                        <ul id="band-suggestions-1"
                          class="max-h-60 absolute z-10 hidden overflow-auto border border-gray-300 bg-white"></ul>
                        <x-text-input id="band_id_1" name="band_id[]" class="hidden" />
                        @error('band')
                          <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                        @enderror
                      </div>
                      <button type="button"
                        class="remove-band mt-7 hidden rounded-lg border border-white bg-white px-4 py-2 font-heading text-black transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">
                        <span class="fas fa-minus"></span>
                      </button>
                      <button type="button" id="add-band-row"
                        class="add-band mt-7 rounded-lg border border-white bg-white px-4 py-2 font-heading text-black transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">
                        <span class="fas fa-plus"></span>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="group mb-4">
                  <x-input-label-dark>Opening Band</x-input-label-dark>
                  <x-text-input id="opener-search" name="opener" autocomplete="off"></x-text-input>
                  <ul id="opener-suggestions"
                    class="max-h-60 absolute z-10 hidden overflow-auto border border-gray-300 bg-white"></ul>
                  <x-text-input id="opener_id" name="opener_id" class="hidden"></x-text-input>
                  @error('opener')
                    <p class="yns_red mt-1 text-sm">{{ $message }}</p>
                  @enderror
                </div>
              </div>
            </div>

            <button type="submit"
              class="mt-7 rounded-lg border border-white bg-white px-4 py-2 font-heading text-black transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>

<script>
  flatpickr('#event_end_time', {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
  });
  flatpickr('#event_start_time', {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
  });
  flatpickr('#merged_date_time', {
    enableTime: true,
    dateFormat: "d-m-Y H:i",
    time_24hr: true,
  });
  flatpickr('#event_date', {
    enableTime: true,
    dateFormat: "d-m-Y H:i",
    time_24hr: true,
  });

  document.getElementById('merged_date_time').addEventListener('change', function(event) {
    event.preventDefault();
    const dateTimeValue = event.target.value;

    const [date, time] = dateTimeValue.split(' ');

    const startDateInput = document.getElementById('event_date');
    const startTimeInput = document.getElementById('event_start_time');

    startDateInput.value = date;
    startTimeInput.value = time;

    // Check if the inputs exist before setting values
    if (startDateInput && startTimeInput) {
      startDateInput.value = date;
      startTimeInput.value = time;
    } else {
      console.error("Start date or time input not found");
    }
  });

  // Poster Preview
  document.getElementById('poster_url').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const img = document.getElementById('posterPreview');
        img.src = e.target.result;
        img.classList.remove('hidden');
      };
      reader.readAsDataURL(file);
    }
  });

  // Venue Search
  const venueInput = document.getElementById('venue_name');
  const suggestionsList = document.getElementById('venue-suggestions');

  venueInput.addEventListener('input', function() {
    const query = this.value;

    if (query.length < 3) {
      suggestionsList.innerHTML = '';
      suggestionsList.classList.add('hidden');
      return;
    }

    const dashboardType = document.getElementById('dashboard_type').value;

    fetch(`/dashboard/${dashboardType}/events/search-venues?query=${encodeURIComponent(query)}`)
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        suggestionsList.innerHTML = '';
        data.forEach(venue => {
          const suggestionItem = document.createElement('li');
          suggestionItem.textContent = venue.name;
          suggestionItem.setAttribute('data-id', venue.id);
          suggestionItem.classList.add(
            'cursor-pointer',
            'hover:text-yns_yellow',
            'px-4',
            'py-2',
            'bg-opac_8_black',
            'text-white'
          );

          // Fixed the event listener setup here
          suggestionItem.addEventListener('click', function() {
            venueInput.value = venue.name;
            document.getElementById('venue_id').value = venue.id;
            suggestionsList.classList.add('hidden');
          });

          suggestionsList.appendChild(suggestionItem);
        });

        if (data.length) {
          suggestionsList.classList.remove('hidden');
        } else {
          suggestionsList.classList.add('hidden');
        }
      })
      .catch(error => {
        console.error('Error fetching venue suggestions:', error);
        suggestionsList.classList.add('hidden');
      });
  });

  // Venue Hide suggestions when clicking outside
  document.addEventListener('click', function(event) {
    if (!venueInput.contains(event.target) && !suggestionsList.contains(event.target)) {
      suggestionsList.classList.add('hidden');
    }
  });

  // Band Search
  const bandsContainer = document.getElementById('bandsContainer');
  const addBandRowButton = document.getElementById('add-band-row');
  let bandRowCount = 1;

  // Update Gap Class if there is more than 1 band row
  function updateGapClass() {
    const bandRows = bandsContainer.querySelectorAll('.band-input-row');
    const removeButtons = bandsContainer.querySelectorAll('.remove-band');

    bandRows.forEach(row => {
      const childDivs = row.querySelectorAll('.flex');

      childDivs.forEach((childDiv) => {
        if (bandRows.length === 1) {
          childDiv.classList.add('gap-275');
          childDiv.classList.remove('gap-1');
        } else {
          childDiv.classList.add('gap-1');
          childDiv.classList.remove('gap-275');
        }
      });
    });

    // Show/Hide remove buttons
    if (bandRows.length > 1) {
      removeButtons.forEach(button => button.classList.remove('hidden'));
    } else {
      if (removeButtons.length > 0) {
        removeButtons[0].classList.add('hidden');
      }
    }
  };

  // Initial class update
  updateGapClass();

  // Function to initialize event listeners for existing band inputs
  function initializeExistingBandInputs() {
    const existingBandInputs = document.querySelectorAll('.band-input');
    existingBandInputs.forEach(input => {
      const suggestionsList = input.nextElementSibling; // Assuming the next sibling is the <ul>

      // Add input event listener to the band input
      input.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length > 0) {
          searchBand(query, `band-suggestions-${bandRowCount}`, 'band', `band_id_${bandRowCount}`,
            bandRowCount);
        } else {
          suggestionsList.classList.add('hidden');
          suggestionsList.innerHTML = ''; // Clear suggestions if the input is empty
        }
      });

      // Add event listener for suggestion clicks
      suggestionsList.addEventListener('click', function(event) {
        if (event.target.tagName === 'LI') {
          const band = {
            id: event.target.dataset.id,
            name: event.target.textContent
          };
          selectBand(band, suggestionsList, 'band', `band_id_${bandRowCount}`);
        }
      });
    });
  }

  // Call the function after the DOM content is fully loaded
  document.addEventListener('DOMContentLoaded', () => {
    initializeExistingBandInputs(); // Initialize existing inputs
    const addBandRowButton = document.getElementById('add-band-row');

    // Existing code for adding new band rows
    addBandRowButton.addEventListener('click', function(event) {
      event.preventDefault();
      createBandRow();
    });
  });

  // Function to create a new band row
  function createBandRow() {
    bandRowCount++;
    const newBandRow = document.createElement('div');
    newBandRow.classList.add('flex', 'items-center', 'mt-4', 'band-input-row');
    newBandRow.innerHTML = `
    <div class="flex flex-row">
      <div class="group w-full">
        <x-input-label-dark>Band</x-input-label-dark>
        <x-text-input id="band_${bandRowCount}" name="band[]" class="band-input"></x-text-input>
        <ul id="band-suggestions-${bandRowCount}" class="hidden max-h-60 absolute z-10 overflow-auto border border-gray-300 bg-white"></ul>
        <x-text-input id="band_id_${bandRowCount}" name="band_id[]" class="hidden"/>
      </div>
      <button type="button" class="mt-7 remove-band mt-7 rounded-lg border border-white bg-white px-4 py-2 font-heading text-black transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow"><span class="fas fa-minus"></span></button>
    </div>
  `;

    bandsContainer.appendChild(newBandRow);
    updateGapClass();

    // Add event listener for the band input to handle searching
    const bandInput = newBandRow.querySelector(`#band_${bandRowCount}`);
    const suggestionsList = newBandRow.querySelector(`#band-suggestions-${bandRowCount}`);

    bandInput.addEventListener('input', function() {
      const query = this.value;
      if (query.length > 0) {
        searchBand(query, `band-suggestions-${bandRowCount}`, 'band', `band_id_${bandRowCount}`);
      } else {
        suggestionsList.classList.add('hidden');
        suggestionsList.innerHTML = ''; // Clear previous suggestions
      }
    });

    // Handle removing the row
    newBandRow.querySelector('.remove-band').addEventListener('click', function() {
      newBandRow.remove();
      updateGapClass();
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    // Initialize event listeners for band input fields
    const bandFields = [{
        inputId: 'headliner-search',
        suggestionsId: 'headliner-suggestions',
        hiddenInputId: 'headliner_id',
        fieldType: 'headliner'
      },
      {
        inputId: 'mainSupport-search',
        suggestionsId: 'mainSupport-suggestions',
        hiddenInputId: 'main_support_id',
        fieldType: 'main-support'
      },
      {
        inputId: 'opener-search',
        suggestionsId: 'opener-suggestions',
        hiddenInputId: 'opener_id',
        fieldType: 'opener'
      }
    ];

    for (let i = 1; i <= bandRowCount; i++) {
      bandFields.push({
        inputId: `band_${i}`,
        suggestionsId: `band-suggestions-${i}`,
        hiddenInputId: `band_id_${i}`,
        fieldType: 'band'
      });
    }

    bandFields.forEach(({
      inputId,
      suggestionsId,
      hiddenInputId,
      fieldType,
    }) => {
      const inputField = document.getElementById(inputId);
      const suggestionsList = document.getElementById(suggestionsId);
      const hiddenInput = document.getElementById(hiddenInputId);

      if (inputField) {
        inputField.addEventListener('input', function() {
          const query = this.value;
          if (query.length > 0) {
            searchBand(query, suggestionsId, fieldType, hiddenInputId); // Pass hiddenInput to searchBand
          } else {
            suggestionsList.classList.add('hidden');
            suggestionsList.innerHTML = ''; // Clear previous suggestions
          }
        });
      }

      if (suggestionsList) {
        suggestionsList.addEventListener('click', (event) => {
          if (event.target.tagName === 'LI') {
            const band = {
              id: event.target.dataset.id,
              name: event.target.textContent
            };
            selectBand(band, suggestionsId, fieldType, hiddenInputId); // Pass hiddenInput directly
          }
        });
      }
    });
  });

  // Search for bands and update suggestions
  function searchBand(query, suggestionsId, fieldType, hiddenInputId) {
    fetch(`/api/bands/search?name=${encodeURIComponent(query)}`)
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        const suggestionsList = document.getElementById(suggestionsId);
        suggestionsList.innerHTML = ''; // Clear previous suggestions

        if (data.length) {
          suggestionsList.classList.remove('hidden');
          data.forEach(band => {
            const listItem = document.createElement('li');
            listItem.textContent = band.name;
            listItem.setAttribute('data-id', band.id);
            listItem.onclick = () => selectBand(band, suggestionsId, fieldType, hiddenInputId);
            listItem.classList.add(
              'cursor-pointer',
              'hover:text-yns_yellow',
              'px-4',
              'py-2',
              'bg-opac_8_black',
              'text-white'
            );
            suggestionsList.appendChild(listItem);
          });
        } else {
          suggestionsList.classList.add('hidden');
        }
      })
      .catch(error => {
        console.error('There was a problem with the fetch operation:', error);
      });
  }

  // Select a band from suggestions
  function selectBand(band, suggestionsId, fieldType, hiddenInputId) {
    const hiddenInput = document.getElementById(hiddenInputId);
    if (hiddenInput) {
      hiddenInput.value = band.id; // Set hidden input value
    }

    // Determine the input field for displaying the band name
    let inputFieldId = '';
    switch (fieldType) {
      case 'headliner':
        inputFieldId = 'headliner-search';
        break;
      case 'main-support':
        inputFieldId = 'mainSupport-search';
        break;
      case 'band':
        const bandIndex = hiddenInputId.split('_')[2];
        inputFieldId = `band_${bandIndex}`;
        break;
      case 'opener':
        inputFieldId = 'opener-search';
        break;
      default:
        console.error(`Unknown field type: ${fieldType}`);
    }

    const inputField = document.getElementById(inputFieldId);
    if (inputField) {
      inputField.value = band.name; // Set the input field value
    }

    // Hide suggestions after selection
    const suggestionsList = document.getElementById(suggestionsId);
    if (suggestionsList) {
      suggestionsList.classList.add('hidden');
      suggestionsList.innerHTML = ''; // Clear the suggestions
    }
  }

  // Promoter Search
  const promoterInput = document.getElementById('promoter_name');
  const promoterSuggestionsList = document.getElementById('promoter-suggestions');

  promoterInput.addEventListener('input', function() {
    const query = this.value;

    if (query.length < 3) {
      promoterSuggestionsList.innerHTML = '';
      promoterSuggestionsList.classList.add('hidden');
      return;
    }

    const dashboardType = document.getElementById('dashboard_type').value;

    fetch(`/dashboard/${dashboardType}/events/search-promoters?query=${encodeURIComponent(query)}`)
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        promoterSuggestionsList.innerHTML = '';
        data.forEach(promoter => {
          const suggestionItem = document.createElement('li');
          suggestionItem.textContent = promoter.name;
          suggestionItem.setAttribute('data-id', promoter.id);
          suggestionItem.classList.add(
            'cursor-pointer',
            'hover:text-yns_yellow',
            'px-4',
            'py-2',
            'bg-opac_8_black',
            'text-white'
          );

          // Event listener for suggestion item click
          suggestionItem.addEventListener('click', function() {
            promoterInput.value = promoter.name; // Set the input value to the selected promoter name
            document.getElementById('promoter_id').value = promoter
              .id; // Set the hidden input value to the selected promoter ID
            promoterSuggestionsList.classList.add('hidden'); // Hide suggestions after selection
          });

          promoterSuggestionsList.appendChild(suggestionItem);
        });

        if (data.length) {
          promoterSuggestionsList.classList.remove('hidden');
        } else {
          promoterSuggestionsList.classList.add('hidden');
        }
      })
      .catch(error => {
        console.error('Error fetching promoter suggestions:', error);
        promoterSuggestionsList.classList.add('hidden');
      });
  });

  // Hide promoter suggestions when clicking outside
  document.addEventListener('click', function(event) {
    if (!promoterInput.contains(event.target) && !promoterSuggestionsList.contains(event.target)) {
      promoterSuggestionsList.classList.add('hidden');
    }
  });

  // Handle form submission
  const eventForm = document.getElementById('eventForm');
  eventForm.addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    const formData = new FormData(this); // Get form data

    // Send the AJAX request
    fetch(eventForm.action, { // Use the action URL of the form
        method: 'POST',
        body: formData,
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token
        },
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        if (data.success) {
          showSuccessNotification(data.message)
          setTimeout(() => {
            window.location.href = data.redirect_url;
          }, 2000);
        } else {
          Object.keys(data.errors).forEach(key => {
            const error = data.errors[key];
            showFailureNotification(error);
          });
        }
      })
      .catch(error => {
        showFailureNotification(error);
      });
  });
</script>