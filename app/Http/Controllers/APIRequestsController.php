<?php

namespace App\Http\Controllers;

use App\Models\OtherService;
use Illuminate\Http\Request;

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
}
