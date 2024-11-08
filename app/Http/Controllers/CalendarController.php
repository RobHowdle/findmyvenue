<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use Google_Client;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Google_Service_Calendar_Event;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Google\Service\Calendar\EventExtendedProperties;
use Google\Service\Calendar as Google_Service_Calendar;

class CalendarController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $this->client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
        $this->client->addScope(Google_Service_Calendar::CALENDAR);
    }

    public function redirectToGoogle()
    {
        $authUrl = $this->client->createAuthUrl();
        return redirect()->away($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        if (!$request) {
            return redirect('/')->with('error', 'Request object is null.');
        }

        if ($request->has('code')) {
            $token = $this->client->fetchAccessTokenWithAuthCode($request->code);
            if (isset($token['access_token'])) {
                $this->client->setAccessToken($token['access_token']);
                Auth::user()->update([
                    'google_access_token' => $token['access_token'],
                    'google_refresh_token' => $token['refresh_token'],
                ]);

                dd(Auth::user()->id);

                return redirect()->route('profile.edit', Auth::user()->id)->with('success', 'Google Calendar linked successfully!');
            } else {
                return redirect('/')->with('error', 'Failed to link Google Calendar: invalid token.');
            }
        }

        return redirect('/')->with('error', 'Failed to link Google Calendar: no authorization code.');
    }

    public function syncGoogleCalendar(Request $request)
    {
        $user = Auth::user();
        $accessToken = $user->google_access_token;

        if (!$accessToken) {
            return redirect()->back()->with('error', 'Google Calendar is not linked.');
        }

        $this->client->setAccessToken($accessToken);

        if ($this->client->isAccessTokenExpired()) {
            if ($user->google_refresh_token) {
                $this->client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
                $user->update(['google_access_token' => $this->client->getAccessToken()]);
            }
        }

        $eventsToSync = [
            [
                'title' => 'Event Title',
                'start' => '2024-10-20T10:00:00-07:00',
                'end' => '2024-10-20T11:00:00-07:00',
            ],
        ];

        $service = new Google_Service_Calendar($this->client);

        foreach ($eventsToSync as $eventData) {
            $event = new Google_Service_Calendar_Event([
                'summary' => $eventData['title'],
                'start' => [
                    'dateTime' => $eventData['start'],
                    'timeZone' => 'America/Los_Angeles',
                ],
                'end' => [
                    'dateTime' => $eventData['end'],
                    'timeZone' => 'America/Los_Angeles',
                ],
            ]);

            try {
                $service->events->insert('primary', $event);
            } catch (Exception $e) {
                return redirect()->back()->with('error', 'Error syncing events: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Calendar synced successfully!');
    }

    public function unlinkGoogle(Request $request)
    {
        $user = Auth::user();
        $user->update(['google_access_token' => null]);
        return redirect()->route('profile.edit', $user->id)->with('success', 'Google Calendar unlinked successfully!');
    }

    public function addEventToCalendar(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'event_id' => 'required|exists:events,id',
                'title' => 'required|string',
                'date' => 'required|date',
                'start_time' => 'required|date_format:H:i:s',
                'end_time' => 'required|date_format:H:i:s|after_or_equal:start_time',
                'location' => 'nullable|string',
                'description' => 'nullable|string',
                'ticket_url' => 'nullable|url',
                'on_the_door_ticket_price' => 'nullable|numeric',
                'calendar_service' => 'required|string'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $date = $validatedData['date'];
        $startTime = $validatedData['start_time'];
        $endTime = $validatedData['end_time'];

        $eventDateOnly = Carbon::parse($date)->format('Y-m-d');
        $startDateTimeString = $eventDateOnly . ' ' . $startTime;
        $endDateTimeString = $eventDateOnly . ' ' . $endTime;

        try {
            $startDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $startDateTimeString);
            $endDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $endDateTimeString);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date/time format.'], 400);
        }

        switch ($request->input('calendar_service')) {
            case 'google':
                return $this->addEventToGoogleCalendar($request);
            case 'apple':
                return $this->addEventToAppleCalendar($request);
            default:
                return response()->json(['error' => 'Unsupported calendar service.'], 400);
        }
    }

    private function addEventToGoogleCalendar(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'event_id' => 'required|exists:events,id',
            'title' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s|after_or_equal:start_time',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
            'ticket_url' => 'nullable|url',
            'on_the_door_ticket_price' => 'nullable|numeric',
        ]);

        $client = new Google_Client();
        $client->setAccessToken($user->google_access_token);

        if ($client->isAccessTokenExpired()) {
            if ($user->google_refresh_token) {
                $client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
                $user->update(['google_access_token' => $client->getAccessToken()]);
            } else {
                return response()->json(['error' => 'No refresh token available.'], 401);
            }
        }

        $service = new Google_Service_Calendar($client);

        $date = Carbon::parse($validatedData['date'])->format('Y-m-d');
        $startTime = trim($validatedData['start_time']);
        $endTime = trim($validatedData['end_time']);

        // Combine date and start_time properly
        $startDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $startTime);

        // Combine date and end_time properly
        $endDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $endTime);

        // Check if the DateTime was created correctly
        if (!$startDateTime || !$endDateTime) {
            throw new \Exception("Invalid DateTime format");
        }

        // Set the timezone
        $startDateTime->setTimezone('Europe/London');
        $endDateTime->setTimezone('Europe/London');

        $extendedProperties = new EventExtendedProperties();
        $extendedProperties->setPrivate([
            'ticket_url' => $validatedData['ticket_url'],
            'on_the_door_ticket_price' => $validatedData['on_the_door_ticket_price'],
        ]);

        $event = new Google_Service_Calendar_Event([
            'summary' => $validatedData['title'],
            'start' => [
                'dateTime' => $startDateTime->format(DateTime::RFC3339),
                'timeZone' => 'Europe/London',
            ],
            'end' => [
                'dateTime' => $endDateTime->format(DateTime::RFC3339),
                'timeZone' => 'Europe/London',
            ],
            'location' => $validatedData['location'],
            'extendedProperties' => $extendedProperties,
        ]);

        try {
            $event = $service->events->insert('primary', $event);
            return response()->json(['success' => true, 'message' => 'Event added successfully!', 'eventId' => $event->id], 200);
        } catch (\Google_Service_Exception $e) {
            return response()->json(['success' => false, 'error' => 'Failed to add event: ' . $e->getMessage()], 500);
        }
    }

    public function checkLinkedCalendars(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $hasGoogleCalendar = !is_null($user->google_access_token);
        $hasAppleCalendar = $user->apple_calendar_synced;

        return response()->json([
            'hasGoogleCalendar' => $hasGoogleCalendar,
            'hasAppleCalendar' => $hasAppleCalendar
        ]);
    }

    public function syncAllEventsToAppleCalendar(Request $request, $eventId)
    {
        $userId = $request->user()->id;
        $user = User::find($userId);
        \Log::info("Syncing events for user ID: $user");

        $event = Event::findOrFail($eventId);
        $startTime = \Carbon\Carbon::parse($event->start_time)->format('Ymd\THis');
        $endTime = \Carbon\Carbon::parse($event->end_time)->format('Ymd\THis');

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        // Create .ics file content
        $icsContent = "
        BEGIN:VCALENDAR
        VERSION:2.0
        PRODID:-//YourNextShow//NONSGML v1.0//EN
        BEGIN:VEVENT
        UID:" . uniqid() . "
        DTSTAMP:" . now()->format('Ymd\THis') . "
        DTSTART:{$startTime}
        DTEND:{$endTime}
        SUMMARY:{$event->event_name}
        DESCRIPTION:{$event->description}
        LOCATION:{$event->location}
        END:VEVENT
        END:VCALENDAR
        ";

        // Define headers for the file download
        $headers = [
            'Content-type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $event->title . '.ics"',
        ];

        if (!$user->apple_calendar_synced) {
            $user->apple_calendar_synced = true;
            $user->save();
        }

        return Response::make($icsContent, 200, $headers);
    }
}
