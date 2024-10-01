@foreach ($todoItems as $item)
  <div class="min-h-52 mx-auto w-full max-w-xs rounded-lg bg-ynsDarkBlue text-white">
    <div class="flex h-full flex-col justify-between rounded-lg border border-ynsRed px-4 py-4">
      <p>{{ $item->item }}</p>
      <div class="mt-4 flex flex-row justify-between">
        <button data-task-id="{{ $item->id }}"
          class="delete-task-btn rounded-lg border border-white bg-ynsDarkGray px-4 py-2 text-white transition duration-150 ease-in-out hover:border-ynsRed hover:text-ynsRed">Delete</button>
        <button data-task-id="{{ $item->id }}"
          class="complete-task-btn rounded-lg border border-white bg-ynsDarkGray px-4 py-2 text-white transition duration-150 ease-in-out hover:border-ynsYellow hover:text-ynsYellow">Complete</button>
      </div>
    </div>
  </div>
@endforeach
