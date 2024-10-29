<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Promoter;
use App\Models\OtherService;
use Illuminate\Http\Request;
use App\Services\TodoService;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    protected $todoService;

    protected function getUserId()
    {
        return Auth::id();
    }

    public function showNotes($dashboardType, Request $request)
    {
        $user = Auth::user()->load(['promoters', 'todos', 'otherService']);
        $perPage = 6;
        $page = $request->input('page', 1);
        $notes = collect();

        if ($dashboardType === 'promoter') {
            $notes = Note::where('serviceable_type', Promoter::class)
                ->whereIn('serviceable_id', $user->promoters->pluck('id'))
                ->where('completed', false)
                ->orderBy('created_at', 'DESC')
                ->paginate($perPage, ['*'], 'page', $page);
        } elseif ($dashboardType === 'band') {
            $notes = Note::where('serviceable_type', OtherService::class)
                ->whereIn('serviceable_id', $user->otherService("Band")->pluck('other_services.id'))
                ->where('completed', false)
                ->orderBy('created_at', 'DESC')
                ->paginate($perPage, ['*'], 'page', $page);
        } elseif ($dashboardType === 'designer') {
            $notes = Note::where('serviceable_type', OtherService::class)
                ->whereIn('serviceable_id', $user->otherService("Designer")->pluck('other_services.id'))
                ->where('completed', false)
                ->orderBy('created_at', 'DESC')
                ->paginate($perPage, ['*'], 'page', $page);
        }

        return view('admin.dashboards.show-notes', [
            'userId' => $this->getUserId(),
            'dashboardType' => $dashboardType,
            'notes' => $notes
        ]);
    }

    public function getNotes($dashboardType, Request $request)
    {
        $user = Auth::user()->load(['promoters', 'notes', 'otherService']);
        $perPage = 6;
        $page = $request->input('page', 1);
        $notes = collect();

        switch ($dashboardType) {
            case 'promoter':
                $promoterCompany = $user->promoters;
                $serviceableId = $promoterCompany->pluck('id');

                if ($promoterCompany->isEmpty()) {
                    return response()->json([
                        'view' => view('components.note-items', ['notes' => collect()])->render(),
                        'hasMore' => false,
                    ]);
                }

                $notes = Note::where('serviceable_type', Promoter::class)
                    ->whereIn('serviceable_id', $serviceableId)
                    ->where('completed', false)
                    ->orderBy('created_at', 'DESC')
                    ->paginate($perPage);

                break;

            case 'band':
                $bandServices = $user->otherService("Band")->get();
                $serviceableId = $bandServices->pluck('other_services.id');

                if ($bandServices->isEmpty()) {
                    return response()->json([
                        'view' => view('components.note-items', ['notes' => collect()])->render(),
                        'hasMore' => false,
                    ]);
                }

                $notes = Note::where('serviceable_type', OtherService::class)
                    ->whereIn('serviceable_id', $serviceableId)
                    ->where('completed', false)
                    ->orderBy('created_at', 'DESC')
                    ->paginate($perPage);

                break;

                // case 'designer':
                //     $designerCompanies = $user->designers;
                //     $serviceableId = $designerCompanies->pluck('id');

                //     if ($designerCompanies->isEmpty()) {
                //         return response()->json([
                //             'view' => view('components.note-items', ['notes' => collect()])->render(),
                //             'hasMore' => false,
                //         ]);
                //     }

                //     $notes = Note::where('serviceable_type', Designer::class)
                //         ->whereIn('serviceable_id', $serviceableId)
                //         ->where('completed', false)
                //         ->orderBy('created_at', 'DESC')
                //         ->paginate($perPage);

                //     break;

            default:
                return response()->json([
                    'view' => view('components.note-items', ['notes' => collect()])->render(),
                    'hasMore' => false,
                ]);
        }

        return response()->json([
            'view' => view('components.note-items', compact('notes'))->render(),
            'hasMore' => $notes->hasMorePages(),
        ]);
    }

    public function newNoteItem($dashboardType, Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string',
            'text' => 'required|string',
            'date' => 'required|date',
            'is_todo' => 'boolean'
        ]);

        $servicealeableType = null;
        $serviceableId = null;

        if ($dashboardType === 'promoter') {
            $servicealeableType = Promoter::class;
            $serviceableId = $user->promoters->first()->id;
        } elseif ($dashboardType === 'band') {
            $servicealeableType = OtherService::class;
            $serviceableId = $user->otherService('Band')->first()->id;
        } elseif ($dashboardType === 'designer') {
            $servicealeableType = OtherService::class;
            $serviceableId = $user->otherService('Designer')->first()->id;
        } elseif ($dashboardType === 'photographer') {
            $servicealeableType = OtherService::class;
            $serviceableId = $user->otherService('Photographer')->first()->id;
        } elseif ($dashboardType === 'videographer') {
            $servicealeableType = OtherService::class;
            $serviceableId = $user->otherService('Videographer')->first()->id;
        } elseif ($dashboardType === 'venue') {
            $servicealeableType = OtherService::class;
            $serviceableId = $user->otherService('Venue')->first()->id;
        } else {
            $servicealeableType = null;
            $serviceableId = null;
        }

        $noteItem = Note::create([
            'serviceable_id' => $serviceableId,
            'serviceable_type' => $servicealeableType,
            'name' => $request->name,
            'text' => $request->text,
            'date' => $request->date,
            'is_todo' => $request->is_todo ?? false,
        ]);

        if ($noteItem->is_todo) {
            $this->todoService->createTodoFromNote($noteItem);
        };

        return response()->json([
            'message' => 'Note Added Successfully',
            'noteItem' => $noteItem,
        ]);
    }

    public function completeNoteItem($dashboardType, $id)
    {
        $note = Note::findOrFail($id);

        $note->completed = true;
        $note->completed_at = now();
        $note->save();

        return response()->json([
            'message' => 'Note marked as completed!',
            'note' => $note,
        ]);
    }

    public function uncompleteNoteItem($dashboardType, $id)
    {
        $noteItem = Note::findOrFail($id);
        $noteItem->completed = false;
        $noteItem->completed_at = null;
        $noteItem->save();

        return response()->json([
            'message' => 'Todo item marked as uncompleted!',
            'noteItem' => $noteItem,
        ]);
    }

    public function showCompletedNoteItems($dashboardType)
    {
        $user = Auth::user()->load(['promoters', 'notes', 'otherService']);
        $perPage = 6;
        $notes = collect();
        $serviceableId = collect();

        switch ($dashboardType) {
            case 'promoter':
                $promoterCompany = $user->promoters;
                $serviceableId = $promoterCompany->pluck('id');

                if ($promoterCompany->isEmpty()) {
                    return response()->json([
                        'view' => view('components.note-items', ['notes' => collect()])->render(),
                        'hasMore' => false,
                    ]);
                }

                // Retrieve completed todo items for the promoter
                $notes = Note::where('serviceable_type', Promoter::class)
                    ->whereIn('serviceable_id', $serviceableId)
                    ->where('completed', true)
                    ->orderBy('created_at', 'DESC')
                    ->paginate($perPage);
                break;

            case 'band':
                $bandServices = $user->otherService("Band");
                $serviceableId = $bandServices->pluck('other_services.id');

                if (!$bandServices) {
                    return response()->json([
                        'view' => view('components.note-items', ['notes' => collect()])->render(),
                        'hasMore' => false,
                    ]);
                }

                // Retrieve completed todo items for the band
                $notes = Note::where('serviceable_type', OtherService::class)
                    ->whereIn('serviceable_id', $serviceableId)
                    ->where('completed', true)
                    ->orderBy('created_at', 'DESC')
                    ->paginate($perPage);
                break;

            default:
                return response()->json([
                    'view' => view('components.note-items', ['notes' => collect()])->render(),
                    'hasMore' => false,
                ]);
        }

        return response()->json([
            'view' => view('components.note-items', ['notes' => $notes])->render(),
            'hasMore' => $notes->hasMorePages(),
        ]);
    }

    public function uncompleteTodoItem($dashboardType, $id)
    {
        // Find the todo item by ID
        $todoItem = Todo::findOrFail($id);

        // Mark the item as completed
        $todoItem->completed = false;
        $todoItem->completed_at = null;
        $todoItem->save();

        // Return a success response
        return response()->json([
            'message' => 'Todo item marked as uncompleted!',
            'todoItem' => $todoItem,
        ]);
    }

    public function deleteNoteItem($dashboardType, $id)
    {
        $noteItem = Note::findOrFail($id);
        $noteItem->delete();

        return response()->json([
            'message' => 'Note deleted successfully!',
        ]);
    }
}