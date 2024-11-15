<?php

namespace App\Services;

use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class TodoService
{
    public function createTodoFromNote($note)
    {
        // \Log::info('Todo Item Created from Note Conversion');

        return Todo::create([
            'user_id' => Auth::user()->id,
            'serviceable_id' => $note->serviceable_id,
            'serviceable_type' => $note->serviceable_type,
            'item' => $note->name . ' - ' . $note->text,
            'due_date' => $note->date,
            'completed' => false,
            'completed_at' => null,
        ]);
    }
}
