<x-app-layout>
  <x-slot name="header">
    <x-promoter-sub-nav :promoter="$promoter" :promoterId="$promoter->id" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg bg-ynsDarkGray text-white">
        <div class="rounded-lg border border-white px-8 py-4">
          <p class="mb-4 font-heading text-4xl font-bold">Todo List</p>
          <form id="newTodoItem" method="POST">
            @csrf
            <div class="flex flex-row items-center gap-8 border-b border-b-white pb-4">
              <div class="group">
                <x-input-label>Item</x-input-label>
                <x-textarea-input class="mt-2 h-32 w-96" id="taskInput" name="task"></x-textarea-input>
              </div>
              <button type="submit" id="addTaskButton"
                class="mt-8 h-10 w-40 rounded-lg border border-white bg-white px-4 py-2 font-heading font-bold text-black transition duration-150 ease-in-out hover:border-ynsYellow hover:text-ynsYellow">Add</button>
            </div>
          </form>
          <div class="grid grid-cols-3 gap-x-4 gap-y-6 pt-6" id="tasks">
            @include('components.todo-items', ['todoItems' => $todoItems])
            @if ($todoItems->isEmpty())
              <p>No todo items found.</p>
            @endif
          </div>
          <div class="mt-6 flex flex-row gap-4">
            <button id="load-more-btn"
              class="h-10 w-40 rounded-lg border border-white bg-white px-4 py-2 font-heading font-bold text-black transition duration-150 ease-in-out hover:border-ynsYellow hover:text-ynsYellow">Load
              More</button>
            <button id="complete-task-btn"
              class="h-10 w-40 rounded-lg border border-white bg-white px-4 py-2 font-heading font-bold text-black transition duration-150 ease-in-out hover:border-ynsYellow hover:text-ynsYellow">View
              Completed</button>
            <button id="uncomplete-task-btn" style="display: none;"
              class="w-50 h-10 rounded-lg border border-white bg-white px-4 py-2 font-heading font-bold text-black transition duration-150 ease-in-out hover:border-ynsYellow hover:text-ynsYellow">View
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
      loadTasks(currentPage);
    });

    $('#complete-task-btn').on('click', function() {
      $('#tasks').empty(); // Clear existing tasks
      currentPage = 1; // Reset to the first page
      loadCompletedTasks();
    });

    $('#uncomplete-task-btn').on('click', function() {
      $('#tasks').empty(); // Clear existing tasks
      currentPage = 1; // Reset to the first page
      loadTasks(currentPage); // Load uncompleted tasks
    });

    // Handle new task submission
    $('#newTodoItem').on('submit', function(e) {
      e.preventDefault();
      let task = $('#taskInput').val();

      $.ajax({
        url: '{{ route('promoter.dashboard.new-todo-item') }}',
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          task: task
        },
        success: function(response) {
          $('#taskInput').val('');
          $('#tasks').empty();
          currentPage = 1;
          loadTasks(currentPage); // Load tasks for the current page
          showSuccessNotification(response.message);
        },
        error: function(xhr) {
          console.log('Error: ', xhr.responseText);
        }
      });
    });

    // Function to load tasks dynamically
    function loadTasks(page) {
      $.ajax({
        url: '{{ route('promoter.dashboard.todo-items') }}',
        type: 'GET',
        data: {
          page: page
        },
        success: function(response) {
          $('#tasks').append(response.view); // Append new tasks
          $('#complete-task-btn').show(); // Hide completed button
          $('#uncomplete-task-btn').hide(); // Show uncompleted button
          $('#load-more-btn').show(); // Optionally hide Load More button

          // Handle visibility of Load More button
          if (!response.hasMore) {
            $('#load-more-btn').hide(); // Hide the button if there are no more tasks
          }
          attachTaskEventListeners(); // Attach listeners for new tasks
        },
        error: function(xhr) {
          console.log('Error: ', xhr.responseText); // Handle error response
        }
      });
    }

    // Completed tasks button
    $('#complete-task-btn').on('click', function() {
      $.ajax({
        url: '{{ route('promoter.dashboard.completed-todo-items') }}', // Adjust route
        type: 'GET',
        success: function(response) {
          $('#tasks').append(response.view);
          $('#load-more-btn').hide(); // Optionally hide Load More button
          attachTaskEventListeners(); // Reattach event listeners for new tasks
        },
        error: function(xhr) {
          console.log('Error: ', xhr.responseText); // Handle error response
        }
      });
    });

    // Attach click event listeners to delete/complete buttons
    function attachTaskEventListeners() {
      $('.delete-task-btn').off('click').on('click', function() {
        let taskId = $(this).data('task-id');
        deleteTask(taskId);
      });

      $('.complete-task-btn').off('click').on('click', function() {
        let taskId = $(this).data('task-id');
        completeTask(taskId);
      });
    }

    // Function to complete a task
    function completeTask(taskId) {
      $.ajax({
        url: '{{ route('promoter.dashboard.complete-todo-item', 'TASK_ID') }}'.replace('TASK_ID', taskId),
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          loadTasks(currentPage); // Reload tasks
          showSuccessNotification(response.message);
        },
        error: function(xhr) {
          showFailureNotification(xhr.responseText);
        }
      });
    }

    // Function to delete a task
    function deleteTask(taskId) {
      $.ajax({
        url: '{{ route('promoter.dashboard.delete-todo-item', 'TASK_ID') }}'.replace('TASK_ID', taskId),
        type: 'DELETE',
        data: {
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          loadTasks(currentPage); // Reload tasks
          showSuccessNotification(response.message);
        },
        error: function(xhr) {
          showFailureNotification(xhr.responseText);
        }
      });
    }

    function loadCompletedTasks() {
      $.ajax({
        url: '{{ route('promoter.dashboard.completed-todo-items') }}',
        type: 'GET',
        success: function(response) {
          $('#tasks').append(response.view);
          $('#complete-task-btn').hide(); // Hide completed button
          $('#uncomplete-task-btn').show(); // Show uncompleted button
          attachTaskEventListeners(); // Reattach event listeners for new tasks
        },
        error: function(xhr) {
          console.log('Error: ', xhr.responseText);
        }
      });
    }
  });
</script>
