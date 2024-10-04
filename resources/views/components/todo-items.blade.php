@foreach ($todoItems as $item)
  <div class="min-h-52 bg-yns_dark_blue mx-auto w-full max-w-xs rounded-lg text-white">
    <div class="flex h-full flex-col justify-between rounded-lg border border-yns_red px-4 py-4">
      <p>{{ $item->item }}</p>
      <div class="mt-4 flex flex-row justify-between">
        <button data-task-id="{{ $item->id }}"
          class="delete-task-btn rounded-lg border border-white bg-yns_dark_gray px-4 py-2 text-white transition duration-150 ease-in-out hover:border-yns_red hover:text-yns_red">Delete</button>
        <button data-task-id="{{ $item->id }}"
          class="complete-task-btn rounded-lg border border-white bg-yns_dark_gray px-4 py-2 text-white transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">Complete</button>
      </div>
    </div>
  </div>
@endforeach
