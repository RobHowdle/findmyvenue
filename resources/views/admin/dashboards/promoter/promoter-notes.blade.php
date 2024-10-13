<x-app-layout>
  <x-slot name="header">
    <x-promoter-sub-nav :promoter="$promoter" :promoterId="$promoter->id" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg bg-yns_dark_gray text-white">
        <div class="rounded-lg border border-white px-8 py-4">
          <p class="mb-4 font-heading text-4xl font-bold">Notes</p>
          <form id="newNote" method="POST">
            @csrf
            <div class="border-b border-b-white pb-4">
              <div class="grid grid-cols-2 gap-12">
                <div class="col">
                  <div class="group mb-4">
                    <x-input-label-dark>Note Name</x-input-label-dark>
                    <x-text-input id="noteInput" name="note"></x-text-input>
                  </div>
                  <div class="group mb-4">
                    <x-input-label>Text</x-input-label>
                    <x-textarea-input id="textInput" name="text" class="w-full"></x-textarea-input>
                  </div>
                </div>
                <div class="col">
                  <div class="group mb-4">
                    <x-input-label-dark>Date</x-input-label-dark>
                    <x-date-input id="dateInput" name="date"></x-date-input>
                  </div>

                  <div class="group mb-4 flex flex-row-reverse items-end justify-end gap-2">
                    <x-input-label-dark>Convert to Todo Item</x-input-label-dark>
                    <x-input-checkbox id="isTodoInput" name="isTodo"></x-input-checkbox>
                  </div>
                </div>
              </div>
              <button type="submit" id="newNoteButton"
                class="h-10 w-40 rounded-lg border border-white bg-white px-4 py-2 font-heading font-bold text-black transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">Add</button>
            </div>
          </form>
          <div class="grid grid-cols-3 gap-x-4 gap-y-6 pt-6" id="notes">
            @include('components.note-items', ['notes' => $notes])
            @if ($notes->isEmpty())
              <p>No notes found.</p>
            @endif
          </div>
          <div class="mt-6 flex flex-row gap-4">
            <button id="load-more-btn"
              class="h-10 w-40 rounded-lg border border-white bg-white px-4 py-2 font-heading font-bold text-black transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">Load
              More</button>
            <button id="completed-note-btn"
              class="h-10 w-40 rounded-lg border border-white bg-white px-4 py-2 font-heading font-bold text-black transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">View
              Completed</button>
            <button id="uncomplete-note-btn" style="display: none;"
              class="w-50 h-10 rounded-lg border border-white bg-white px-4 py-2 font-heading font-bold text-black transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">View
              Uncompleted</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
<script>
  $(document).ready(function() {
    let currentPage = 1;

    // Load initial notes
    loadNotes(currentPage);

    // Delegate click events for dynamically added buttons
    $(document).on('click', '.complete-note-btn', function() {
      let noteId = $(this).data('note-id');
      completeNote(noteId);
    });

    $(document).on('click', '.delete-note-btn', function() {
      let noteId = $(this).data('note-id');
      deleteNote(noteId);
    });

    $('#load-more-btn').on('click', function() {
      currentPage++;
      loadNotes(currentPage);
    });

    $('#complete-note-btn').on('click', function() {
      currentPage = 1; // Reset to the first page
      loadCompletedNotes(); // Load completed notes
    });

    $('#uncomplete-note-btn').on('click', function() {
      currentPage = 1; // Reset to the first page
      loadNotes(currentPage); // Load uncompleted notes
    });

    // Handle new note submission
    $('#newNote').on('submit', function(e) {
      e.preventDefault();

      // Get values from the input fields
      let noteName = $('#noteInput').val();
      let noteText = $('#textInput').val();
      let noteDate = $('#dateInput').val();
      let isTodo = $('#isTodoInput').is(':checked') ? 1 : 0;

      $.ajax({
        url: `{{ route('dashboard.store-new-note') }}`,
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          name: noteName,
          text: noteText,
          date: noteDate,
          is_todo: isTodo // Use the correct key name for the backend
        },
        success: function(response) {
          // Clear input fields
          $('#noteInput').val('');
          $('#textInput').val('');
          $('#dateInput').val('');
          $('#isTodoInput').prop('checked', false);
          loadNotes(1); // Reload notes from the first page after a new note is added
          showSuccessNotification(response.message);
        },
        error: function(xhr) {
          console.log('Error: ', xhr.responseText);
        }
      });
    });

    // Function to load notes dynamically
    function loadNotes(page) {
      $.ajax({
        url: '{{ route('admin.promoter.dashboard.note-items') }}',
        type: 'GET',
        data: {
          page: page
        },
        success: function(response) {
          if (page === 1) {
            $('#notes').empty(); // Clear existing notes only on the first load
          }
          $('#notes').append(response.view); // Append new notes

          // Handle visibility of Load More button
          if (!response.hasMore) {
            $('#load-more-btn').hide(); // Hide the button if there are no more notes
          }
        },
        error: function(xhr) {
          console.log('Error: ', xhr.responseText); // Handle error response
        }
      });
    }

    // Function to complete a note
    function completeNote(noteId) {
      $.ajax({
        url: '{{ route('admin.promoter.dashboard.complete-note', 'NOTE_ID') }}'.replace('NOTE_ID', noteId),
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          loadNotes(currentPage); // Reload notes
          showSuccessNotification(response.message);
        },
        error: function(xhr) {
          showFailureNotification(xhr.responseText);
        }
      });
    }

    // Function to delete a note
    function deleteNote(noteId) {
      $.ajax({
        url: '{{ route('admin.promoter.dashboard.delete-note', 'NOTE_ID') }}'.replace('NOTE_ID', noteId),
        type: 'DELETE',
        data: {
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          loadNotes(currentPage); // Reload notes
          showSuccessNotification(response.message);
        },
        error: function(xhr) {
          showFailureNotification(xhr.responseText);
        }
      });
    }

    function loadCompletedNotes() {
      $.ajax({
        url: '{{ route('admin.promoter.dashboard.completed-notes') }}',
        type: 'GET',
        success: function(response) {
          $('#notes').empty(); // Clear existing notes
          $('#notes').append(response.view); // Append new completed notes
          $('#complete-note-btn').hide(); // Hide completed button
          $('#uncomplete-note-btn').show(); // Show uncompleted button
        },
        error: function(xhr) {
          console.log('Error: ', xhr.responseText);
        }
      });
    }
  });
</script>
