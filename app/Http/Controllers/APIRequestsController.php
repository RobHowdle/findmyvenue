<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
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

    public function getUserCalendarEvents(Request $request, $user)
    {
        // Verify that a promoter exists for the logged-in user
        $currentUser = Auth::user();  // Get the current user
        $promoter = $currentUser->promoters()->first();  // Get the promoter for this user

        if (!$promoter) {
            return response()->json(['success' => false, 'message' => 'Promoter Not Found'], 404);
        }

        if ($request->query('view') === 'calendar') {
            $start = $request->query('start');
            $end = $request->query('end');

            // Fetch events based on the date range
            $events = Event::with(['bands', 'services', 'venues'])
                ->whereHas('promoters', function ($query) use ($promoter) {
                    $query->where('promoter_id', $promoter->id);
                })
                ->whereBetween('event_date', [$start, $end])
                ->get();

            $formattedEvents = $events->map(function ($event) {
                // Only use the event date
                $eventDate = Carbon::parse($event->event_date)->format('Y-m-d');

                return [
                    'title' => $event->name,
                    'start' => $eventDate . 'T' . $event->event_start_time,
                    'end' => $eventDate . 'T' . $event->event_end_time,
                    'description' => $event->event_description,
                    'event_start_time' => $event->event_start_time,
                    'bands' => $event->services->map(function ($band) {
                        return $band->name;
                    })->toArray(),
                    'location' => $event->venues->first()->location,
                    'ticket_url' => $event->ticket_url,
                    'on_the_door_ticket_price' => $event->on_the_door_ticket_price,
                ];
            });

            return response()->json([
                'success' => true,
                'events' => $formattedEvents,
            ]);
        }

        return response()->json(['success' => true, 'events' => []]);
    }
}
