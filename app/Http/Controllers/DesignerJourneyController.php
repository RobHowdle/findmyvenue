<?php

namespace App\Http\Controllers;

use App\Models\OtherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DesignerJourneyController extends Controller
{
    protected function getUserId()
    {
        return Auth::id();
    }

    public function index($dashboardType)
    {
        $modules = collect(session('modules', []));
        $designer = OtherService::designers()->get();

        return view('admin.dashboards.designer.designer-journey', [
            'userId' => $this->getUserId(),
            'dashboardType' => $dashboardType,
            'modules' => $modules,
            'designer' => $designer,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $designers = OtherService::designers()->where('name', 'LIKE', '%' . $query . '%')->get();

        return response()->json([
            'results' => $designers,
            'count' => $designers->count()
        ]);
    }

    public function joinDesigner($dashboardType, Request $request)
    {
        $designerId = $request->input('serviceable_id');
        $user = Auth::user();

        // Check designer exists
        $designer = OtherService::find($designerId);

        if (!$designer) {
            return response()->json([
                'success' => false,
                'message' => 'The designer does not exist.'
            ], 400);
        }

        if ($user->otherService('designer')->where('serviceable_id', $designerId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You are already linked'
            ], 400);
        }

        $user->otherService('designer')->attach($designerId);

        return response()->json([
            'success' => true,
            'message' => 'Successfully linked!',
            'redirect' => route('dashboard', ['dashboardType' => $dashboardType])
        ], 200);
    }
}
