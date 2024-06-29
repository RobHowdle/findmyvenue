<?php

namespace App\Http\Controllers;

use App\Models\OtherService;
use Illuminate\Http\Request;
use App\Models\OtherServiceList;
use Illuminate\Support\Facades\DB;

class OtherServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchQuery = $request->input('search_query');

        $otherServices = OtherService::with('otherServiceList')
            ->select('other_service_id')
            ->whereNull('deleted_at')
            ->when($searchQuery, function ($query, $searchQuery) {
                return $query->where('postal_town', 'like', "%$searchQuery%");
            })
            ->groupBy('other_service_id')
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'otherServices' => $otherServices,
                'view' => view('partials.other-service-list', compact('otherServices'))->render()
            ]);
        }

        return view('other', compact('otherServices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function showGroup($serviceName)
    {
        $otherService = OtherService::whereHas('otherServiceList', function ($query) use ($serviceName) {
            $query->where('service_name', $serviceName);
        })->with('otherServiceList')->get();

        $otherTitle = OtherServiceList::where('service_name', $serviceName)->first();

        return view('single-service-group', compact('otherService', 'otherTitle'));
    }


    /**
     * Display the specified resource.
     */
    public function show($serviceName, $serviceId)
    {
        $singleService = OtherService::where('id', $serviceId)->with('otherServiceList')->first();
        $singleServiceTitle =
            OtherService::where('id', $serviceId)->with('otherServiceList')->first();

        // Split the field containing multiple URLs into an array
        if ($singleService->contact_link) {
            $urls = explode(',', $singleService->contact_link);
            $platforms = [];
        }

        // // Check each URL against the platforms
        foreach ($urls as $url) {
            // Initialize the platform as unknown
            $matchedPlatform = 'Unknown';

            // Check if the URL contains platform names
            $platformsToCheck = ['facebook', 'twitter', 'instagram'];
            foreach ($platformsToCheck as $platform) {
                if (stripos($url, $platform) !== false) {
                    $matchedPlatform = $platform;
                    break; // Stop checking once a platform is found
                }
            }

            // Store the platform information for each URL
            $platforms[] = [
                'url' => $url,
                'platform' => $matchedPlatform
            ];
        }

        $singleService->platforms = $platforms;


        return view('single-service', compact('singleService', 'singleServiceTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OtherService $otherService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OtherService $otherService)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OtherService $otherService)
    {
        //
    }
}
