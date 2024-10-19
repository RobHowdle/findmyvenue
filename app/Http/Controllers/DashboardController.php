<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use App\Models\Venue;
use App\Models\UserService;
use App\Models\VenueReview;
use Illuminate\Http\Request;
use App\Services\TodoService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    protected $todoService;

    public function __construct(TodoService $todoService)
    {
        $this->todoService = $todoService;
    }

    public function index(Request $request)
    {
        $user = Auth::user()->load(['roles', 'promoters']);
        $role = $user->roles->pluck('name');
        $roleName = !empty($role) ? $role[0] : null;
        $venues = Venue::all();

        $promotionsCompany = $user->promoters()->first();

        $genreList = file_get_contents(public_path('text/genre_list.json'));
        $data = json_decode($genreList, true);
        $genres = $data['genres'];

        switch ($roleName) {
            case 'promoter':
                if (!$promotionsCompany) {
                    return view('admin.dashboards.promoter.promoter-new-service', compact([
                        'venues',
                        'genres'
                    ]));
                }
                return redirect()->route('promoter.dashboard');
            case 'artist':
                return redirect()->route('artist.dashboard');
            case 'venue':
                return redirect()->route('venue.dashboard');
            case 'administrator':
                return redirect()->route('admin.dashboard');
            default:
                return abort(403); // Forbidden access if role is not recognized
        }
    }

    public function editUser(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        dd($user);
    }

    public function approveDisplayVenueReview($reviewId)
    {
        $review = VenueReview::findOrFail($reviewId);

        $review->update([
            'review_approved' => 1,
            'display' => 1
        ]);

        return redirect()->route('dashboard')->with('success', 'Review approved and set to display.');
    }

    public function approveVenueReview($reviewId)
    {
        $review = VenueReview::findOrFail($reviewId);

        $review->update([
            'review_approved' => 1,
        ]);

        return redirect()->route('dashboard')->with('success', 'Review approved.');
    }

    public function userServiceLink(Request $request)
    {
        try {
            // Retrieve the user
            $user = User::findOrFail($request->input('user_select'));
            $typeSelect = $request->input('type-select');
            $serviceId = $request->input('service_select_id');
            // Initialize the variables as null
            $venueId = null;
            $promoterId = null;
            $otherServiceId = null;

            // Determine which type was selected and set the appropriate ID
            if ($typeSelect == 'venues') {
                $venueId = $serviceId;
            } elseif ($typeSelect == 'promoters') {
                $promoterId = $serviceId;
            } elseif ($typeSelect == 'other_service') {
                $otherServiceId = $serviceId;
            }

            // Create the UserService record based on the selected type
            if ($venueId) {
                $userService = UserService::create([
                    'user_id' => $user->id,
                    'venues_id' => $venueId
                ]);
            } elseif ($promoterId) {
                $userService = UserService::create([
                    'user_id' => $user->id,
                    'promoters_id' => $promoterId
                ]);
            } elseif ($otherServiceId) {
                $userService = UserService::create([
                    'user_id' => $user->id,
                    'other_service_id' => $otherServiceId
                ]);
            } else {
                dd('This has fucked up');
            }

            return redirect()->route('dashboard')->with('success', 'User successfully linked');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while linking the user');
        }
    }

    public function storeNewNote(Request $request)
    {
        try {
            $promoter = Auth::user()->promoters()->first();

            if (!$promoter) {
                return response()->json([
                    'success' => false,
                    'message' => 'Promoter not found'
                ], 404);
            }

            $promoterId = $promoter->id;
            $serviceableType = Auth::user()->load('roles')->getRoleType();

            // Validate the incoming request data
            $validatedData = $request->validate([
                'name' => 'required|string',
                'text' => 'required|string',
                'date' => 'required|date',
                'is_todo' => 'boolean'
            ]);

            // Create the note
            $note = Note::create([
                'serviceable_id' => $promoterId,
                'serviceable_type' => $serviceableType,
                'name' => $validatedData['name'],
                'text' => $validatedData['text'],
                'date' => $validatedData['date'],
                'is_todo' => $validatedData['is_todo'] ?? false,
            ]);

            if ($note->is_todo) {
                $this->todoService->createTodoFromNote($note);
            };

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Note Created Successfully'
            ]);
        } catch (\Exception $e) {
            // Log the error message for debugging
            Log::error('Error creating note:', ['message' => $e->getMessage()]);

            // Return error response
            return response()->json(['success' => false, 'message' => 'Error creating note: ' . $e->getMessage()], 400);
        }
    }
}
