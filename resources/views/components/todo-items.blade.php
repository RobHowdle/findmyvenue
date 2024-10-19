@foreach ($todoItems as $item)
  <div class="min-h-52 mx-auto w-full max-w-xs rounded-lg bg-yns_dark_blue text-white">
    <div class="flex h-full flex-col justify-between rounded-lg border border-yns_red px-4 py-4">
      <p>Todo: {{ $item->item }}</p>
      <p class="mb-2">Created On: {{ $item->created_at->format('d-m-Y') }}</p>
      <div class="mt-1 flex flex-row justify-between">
        <button data-task-id="{{ $item->id }}" id="delete-task-btn"
          class="delete-task-btn rounded-lg border border-white bg-yns_dark_gray px-4 py-2 text-white transition duration-150 ease-in-out hover:border-yns_red hover:text-yns_red">Delete</button>
        @if ($item->completed === true)
          <button data-task-id="{{ $item->id }}" id="uncomplete-task-btn"
            class="uncomplete-task-btn rounded-lg border border-white bg-yns_dark_gray px-4 py-2 text-white transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">Uncomplete</button>
        @else
          <button data-task-id="{{ $item->id }}" id="complete-task-btn"
            class="complete-task-btn rounded-lg border border-white bg-yns_dark_gray px-4 py-2 text-white transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">Complete</button>
        @endif
      </div>
    </div>
  </div>
@endforeach
