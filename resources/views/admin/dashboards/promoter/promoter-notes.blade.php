<x-app-layout>
  <x-slot name="header">
    {{-- <x-sub-nav :promoter="$promoter" :promoterId="$promoter->id" /> --}}
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg bg-yns_dark_gray text-white">
        <div class="rounded-lg border border-white px-8 py-4">
          <p class="mb-4 font-heading text-4xl font-bold">Notes</p>
          <form id="newNote" method="POST">
            @csrf
            <div class="border-b border-b-white pb-4">
              <div class="grid grid-cols-2 gap-x-12">
                <div class="col">
                  <div class="group mb-4">
                    <x-input-label-dark>Note Name</x-input-label-dark>
                    <x-text-input id="noteInput" name="note"></x-text-input>
                  </div>
                </div>
                <div class="col">
                  <div class="group mb-4">
                    <x-input-label-dark>Date</x-input-label-dark>
                    <x-date-input id="dateInput" name="date"></x-date-input>
                  </div>
                </div>

                <div class="col-span-2">
                  <div class="group mb-4">
                    <x-input-label>Text</x-input-label>
                    <x-textarea-input id="textInput" name="text" class="w-full"></x-textarea-input>
                  </div>
                </div>

                <div class="col-span-2 flex flex-row-reverse items-end justify-end gap-2">
                  <x-input-label>Convert to Todo Item</x-input-label>
                  <x-input-checkbox id="isTodoInput" name="isTodo"></x-input-checkbox>
                </div>
              </div>
              <button type="submit" id="newNoteButton"
                class="mt-4 h-10 w-40 rounded-lg border border-white bg-white px-4 py-2 font-heading font-bold text-black transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">Add</button>
            </div>
          </form>
          <div class="grid grid-cols-3 gap-x-4 gap-y-6 pt-6" id="notes">
            @if ($notes->isEmpty())
              <p>No notes found.</p>
            @else
              @include('components.note-items', ['notes' => $notes])
            @endif
          </div>
          <div class="mt-6 flex flex-row gap-4">
            <button id="load-more-btn"
              class="h-10 w-40 rounded-lg border border-white bg-white px-4 py-2 font-heading font-bold text-black transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">Load
              More</button>
            <button id="completed-notes-btn"
              class="h-10 w-40 rounded-lg border border-white bg-white px-4 py-2 font-heading font-bold text-black transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">View
              Completed</button>
            <button id="uncompleted-notes-btn" style="display: none;"
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

    $('#load-more-btn').on('click', function() {
      currentPage++;
      loadNotes(currentPage);
    });

    $(document).on('click', '.complete-note-btn', function() {
      const noteId = $(this).data('note-id');
      const noteElement = $(this).closest('.note-item')
      completeNote(noteId);

      $('#notes').empty();
      currentPage = 1;
    });

    $(document).on('click', '.uncomplete-note-btn', function() {
      const noteId = $(this).data('note-id');
      const noteElement = $(this).closest('.note-item')
      uncompleteNote(noteId);

      $('#notes').empty();
      currentPage = 1;
    });

    $(document).on('click', '.delete-note-btn', function() {
      const noteId = $(this).data('note-id');
      const noteElement = $(this).closest('.note-item')
      deleteNote(noteId);

      $('#notes').empty();
      currentPage = 1;
    });

    $('#completed-notes-btn').on('click', function() {
      currentPage = 1;
      loadCompletedNotes();
    });

    $('#uncompleted-notes-btn').on('click', function() {
      currentPage = 1;
      loadNotes(currentPage);
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
          $('#noteInput').val('');
          $('#textInput').val('');
          $('#dateInput').val('');
          $('#isTodoInput').prop('checked', false);
          currentPage = 1;
          loadNotes(currentPage);
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
          // If it's the first page, clear existing notes
          if (page === 1) {
            $('#notes').empty(); // Clear existing notes
          }

          $('#notes').append(response.view); // Append new notes

          // Show or hide the buttons based on response
          $('#completed-notes-btn').show();
          $('#uncompleted-notes-btn').hide();
          $('#load-more-btn').show();

          if (!response.hasMore) {
            $('#load-more-btn').hide();
          }
        },
        error: function(xhr) {
          console.log('Error: ', xhr.responseText);
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
          showSuccessNotification(response.message);
          setInterval(() => {
            loadNotes(currentPage);
          }, 500);
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
          showSuccessNotification(response.message);
          setInterval(() => {
            loadNotes(currentPage);
          }, 500);
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
          $('#notes').append(response.view); // Append new completed notes
          $('#completed-notes-btn').hide(); // Hide completed button
          $('#uncompleted-notes-btn').show(); // Show uncompleted button
          $('#load-more-btn').show();

          if (!response.hasMore) {
            $('#load-more-btn').hide();
          }
        },
        error: function(xhr) {
          console.log('Error: ', xhr.responseText);
        }
      });
    }
  });
</script>
