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

    public function getUserCalendarEvents($dashboardType, Request $request, $userId)
    {
        // Fetch the current user along with relationships
        $currentUser = User::with(['promoters', 'venues', 'otherService'])->find($userId);

        $service = '';
        switch ($dashboardType) {
            case 'promoter':
                $service = $currentUser->promoters()->first();
                break;
            case 'venue':
                $service = $currentUser->venues()->first();
                break;
            case 'artist':
                $service = $currentUser->otherService('Artist')->first();
                break;
            case 'designer':
                $service = $currentUser->otherService('Designer')->first();
                break;
            case 'photographer':
                $service = $currentUser->otherService('Photographer')->first();
                break;
            case 'videographer':
                $service = $currentUser->otherService('Videographer')->first();
                break;
            default:
                return response()->json(['success' => false, 'message' => 'Invalid Dashboard Type'], 400);
        }

        if (!$service) {
            return response()->json(['success' => false, 'message' => 'Service Not Found'], 404);
        }

        if ($request->query('view') === 'calendar') {
            $start = $request->query('start');
            $end = $request->query('end');

            // Fetch events based on the service type
            $events = Event::with(['artist', 'services', 'venues'])
                ->where(function ($query) use ($dashboardType, $service) {
                    switch ($dashboardType) {
                        case 'promoter':
                            $query->whereHas('promoters', function ($subQuery) use ($service) {
                                $subQuery->where('promoter_id', $service->id);
                            });
                            break;
                        case 'venue':
                            $query->whereHas('venues', function ($subQuery) use ($service) {
                                $subQuery->where('venue_id', $service->id);
                            });
                            break;
                        case 'artist':
                            $query->whereHas('bands', function ($subQuery) use ($service) {
                                $subQuery->where('band_id', $service->id);
                            });
                            break;
                        default:
                            break;
                    }
                })
                ->whereBetween('event_date', [$start, $end])
                ->get();

            // Format events for the calendar view
            $formattedEvents = $events->map(function ($event) {
                $eventDate = Carbon::parse($event->event_date)->format('Y-m-d');

                return [
                    'title' => $event->event_name,
                    'start' => $eventDate . 'T' . $event->event_start_time,
                    'end' => $eventDate . 'T' . $event->event_end_time,
                    'description' => $event->event_description,
                    'event_start_time' => $event->event_start_time,
                    'bands' => $event->services->map(function ($band) {
                        return $band->name;
                    })->toArray(),
                    'location' => $event->venues->first()->location ?? 'No location provided',
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