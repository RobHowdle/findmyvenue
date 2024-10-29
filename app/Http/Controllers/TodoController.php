<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\Promoter;
use App\Models\OtherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    protected function getUserId()
    {
        return Auth::id();
    }

    public function showTodos($dashboardType, Request $request)
    {
        // Load the authenticated user with their associated promoters and todos
        $user = Auth::user()->load(['promoters', 'todos', 'otherService']);

        $perPage = 6;
        $page = $request->input('page', 1);

        $todoItems = collect();

        if ($dashboardType === 'promoter') {
            $todoItems = Todo::where('serviceable_type', Promoter::class)
                ->whereIn('serviceable_id', $user->promoters->pluck('id'))
                ->where('completed', false)
                ->orderBy('created_at', 'DESC')
                ->paginate($perPage, ['*'], 'page', $page);
        } elseif ($dashboardType === 'band') {
            $todoItems = Todo::where('serviceable_type', OtherService::class)
                ->whereIn('serviceable_id', $user->otherService("Band")->pluck('other_services.id'))
                ->where('completed', false)
                ->orderBy('created_at', 'DESC')
                ->paginate($perPage, ['*'], 'page', $page);
        } elseif ($dashboardType === 'designer') {
            $todoItems = Todo::where('serviceable_type', OtherService::class)
                ->whereIn('serviceable_id', $user->otherService("Designer")->pluck('other_services.id'))
                ->where('completed', false)
                ->orderBy('created_at', 'DESC')
                ->paginate($perPage, ['*'], 'page', $page);
        }

        return view('admin.dashboards.todo-list', [
            'userId' => $this->getUserId(),
            'dashboardType' => $dashboardType,
            'todoItems' => $todoItems,
        ]);
    }

    public function getTodos($dashboardType, Request $request)
    {
        $user = Auth::user()->load(['promoters', 'todos', 'otherService']);
        $perPage = 6;
        $page = $request->input('page', 1);
        $todoItems = collect();

        switch ($dashboardType) {
            case 'promoter':
                $promoterCompany = $user->promoters;
                $serviceableId = $promoterCompany->pluck('id');

                if ($promoterCompany->isEmpty()) {
                    return response()->json([
                        'view' => view('components.todo-items', ['todoItems' => collect()])->render(),
                        'hasMore' => false,
                    ]);
                }

                $todoItems = Todo::where('serviceable_type', Promoter::class)
                    ->whereIn('serviceable_id', $serviceableId)
                    ->where('completed', false)
                    ->orderBy('created_at', 'DESC')
                    ->paginate($perPage);

                break;

            case 'band':
                $bandServices = $user->otherService("Band");
                $serviceableId = $bandServices->pluck('other_services.id');

                if (!$bandServices) {
                    return response()->json([
                        'view' => view('components.todo-items', ['todoItems' => collect()])->render(),
                        'hasMore' => false,
                    ]);
                }

                $todoItems = Todo::where('serviceable_type', OtherService::class)
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
                //             'view' => view('components.todo-items', ['todoItems' => collect()])->render(),
                //             'hasMore' => false,
                //         ]);
                //     }

                //     $todoItems = Todo::where('serviceable_type', Designer::class)
                //         ->whereIn('serviceable_id', $serviceableId)
                //         ->where('completed', false)
                //         ->orderBy('created_at', 'DESC')
                //         ->paginate($perPage);

                //     break;

            default:
                return response()->json([
                    'view' => view('components.todo-items', ['todoItems' => collect()])->render(),
                    'hasMore' => false,
                ]);
        }

        return response()->json([
            'view' => view('components.todo-items', compact('todoItems'))->render(),
            'hasMore' => $todoItems->hasMorePages(),
        ]);
    }

    public function newTodoItem($dashboardType, Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'task' => 'required|string'
        ]);

        $servicaleableType = null;
        $serviceableId = null;

        if ($dashboardType === 'promoter') {
            $servicaleableType = Promoter::class;
            $serviceableId = $user->promoters->first()->id;
        } elseif ($dashboardType === 'band') {
            $servicaleableType = OtherService::class;
            $serviceableId = $user->otherService('Band')->first()->id;
        } elseif ($dashboardType === 'designer') {
            $servicaleableType = OtherService::class;
            $serviceableId = $user->otherService('Designer')->first()->id;
        } elseif ($dashboardType === 'photographer') {
            $servicaleableType = OtherService::class;
            $serviceableId = $user->otherService('Photographer')->first()->id;
        } elseif ($dashboardType === 'videographer') {
            $servicaleableType = OtherService::class;
            $serviceableId = $user->otherService('Videographer')->first()->id;
        } elseif ($dashboardType === 'venue') {
            $servicaleableType = OtherService::class;
            $serviceableId = $user->otherService('Venue')->first()->id;
        } else {
            $servicaleableType = null;
            $serviceableId = null;
        }

        $todoItem = Todo::create([
            'user_id' => $user->id,
            'serviceable_id' => $serviceableId,
            'serviceable_type' => $servicaleableType,
            'item' => $request->task,
        ]);

        return response()->json([
            'message' => 'Item Added Successfully',
            'todoItem' => $todoItem,
        ]);
    }

    public function completeTodoItem($dashboardType, $id)
    {

        $todoItem = Todo::findOrFail($id);

        $todoItem->completed = true;
        $todoItem->completed_at = now();
        $todoItem->save();

        // Return a success response
        return response()->json([
            'message' => 'Todo item marked as completed!',
            'todoItem' => $todoItem,
        ]);
    }

    public function deleteTodoItem($dashboardType, $id)
    {
        $todoItem = Todo::findOrFail($id);
        $todoItem->delete();

        return response()->json([
            'message' => 'Todo item deleted successfully!',
        ]);
    }

    public function showCompletedTodoItems($dashboardType)
    {
        $user = Auth::user()->load(['promoters', 'todos', 'otherService']);
        $perPage = 6;
        $todoItems = collect();
        $serviceableId = collect();

        switch ($dashboardType) {
            case 'promoter':
                $promoterCompany = $user->promoters;
                $serviceableId = $promoterCompany->pluck('id');

                if ($promoterCompany->isEmpty()) {
                    return response()->json([
                        'view' => view('components.todo-items', ['todoItems' => collect()])->render(),
                        'hasMore' => false,
                    ]);
                }

                // Retrieve completed todo items for the promoter
                $todoItems = Todo::where('serviceable_type', Promoter::class)
                    ->whereIn('serviceable_id', $serviceableId)
                    ->where('completed', true) // Change to true
                    ->orderBy('created_at', 'DESC')
                    ->paginate($perPage);
                break;

            case 'band':
                $bandServices = $user->otherService("Band");
                $serviceableId = $bandServices->pluck('other_services.id');

                if (!$bandServices) {
                    return response()->json([
                        'view' => view('components.todo-items', ['todoItems' => collect()])->render(),
                        'hasMore' => false,
                    ]);
                }

                // Retrieve completed todo items for the band
                $todoItems = Todo::where('serviceable_type', OtherService::class)
                    ->whereIn('serviceable_id', $serviceableId)
                    ->where('completed', true)
                    ->orderBy('created_at', 'DESC')
                    ->paginate($perPage);
                break;

            default:
                return response()->json([
                    'view' => view('components.todo-items', ['todoItems' => collect()])->render(),
                    'hasMore' => false,
                ]);
        }

        return response()->json([
            'view' => view('components.todo-items', ['todoItems' => $todoItems])->render(),
            'hasMore' => $todoItems->hasMorePages(),
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
}