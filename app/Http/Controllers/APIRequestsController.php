<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\OtherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class APIRequestsController extends Controller
{
    public function searchBands(Request $request)
    {
        $query = $request->input('name');

        if (empty($query)) {
            return response()->json(['error' => 'Query is required'], 400);
        }

        $bands = OtherService::where('other_service_id', 4)
            ->where('name', 'LIKE', '%' . $query . '%')
            ->get(['id', 'name']);

        return response()->json($bands);
    }

    /**
     * Get Users Calendar Events
     */

    public function getUserCalendarEvents(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'User not authenticated.'], 401);
        }

        // Get the authenticated user
        $user = Auth::user();
        $promoter = $user->promoters()->first();

        if (!$promoter) {
            return response()->json(['success' => false, 'message' => 'Promoter Not Found'], 404);
        }

        // Check if the request wants calendar events
        if ($request->query('view') === 'calendar') {
            // Fetch events as before
            $events = Event::with(['bands', 'venues'])
                ->whereHas('promoters', function ($query) use ($promoter) {
                    $query->where('promoter_id', $promoter->id);
                })
                ->get();

            $formattedEvents = $events->map(function ($event) {
                return [
                    'title' => $event->name,
                    'start' => $event->event_date . 'T' . $event->event_start_time,
                    'end' => $event->event_date . 'T' . $event->event_end_time,
                    'description' => $event->event_description,
                ];
            });

            return response()->json([
                'success' => true,
                'events' => $formattedEvents,
            ]);
        }
    }
}
