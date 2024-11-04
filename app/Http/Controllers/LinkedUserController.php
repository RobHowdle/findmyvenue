<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Venue;
use App\Models\Promoter;
use App\Models\ServiceUser;
use App\Models\OtherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LinkedUserController extends Controller
{
    protected function getUserId()
    {
        return Auth::id();
    }

    public function showUsers($dashboardType)
    {
        $modules = collect(session('modules', []));

        return view('admin.dashboards.show-users', [
            'userId' => $this->getUserId(),
            'dashboardType' => $dashboardType,
            'modules' => $modules
        ]);
    }

    public function getUsers($dashboardType)
    {
        $modules = collect(session('modules', []));
        $user = Auth::user()->load(['promoters', 'otherService']);

        $relatedUsers = null;

        if ($dashboardType == 'promoter') {
            $relatedUsers = $user->promoters->load(['linkedUsers']);
        } elseif ($dashboardType == 'band') {
            $relatedUsers = $user->otherService("Band")->get();
        }

        return response()->json([
            'userId' => $this->getUserId(),
            'dashboardType' => $dashboardType,
            'relatedUsers' => $relatedUsers,
            'modules' => $modules,
        ]);
    }

    public function newUser($dashboardType)
    {
        $modules = collect(session('modules', []));
        $user = Auth::user();
        $role = $user->roles->first()->name;
        $service = null;
        $currentServiceId = null;

        switch ($role) {
            case 'promoter':
                $service = $user->promoters()->first();
                $currentServiceId = $user->promoters->first()->id ?? null;
                break;

            case 'venue':
                $service = $user->venues()->first();
                $currentServiceId = $user->venues->first()->id ?? null;
                break;

            case 'band':
                $service = $user->otherService("Band")->first();
                $currentServiceId = $user->otherService("Band")->first()->id ?? null;
                break;

            case 'photographer':
                $service = $user->otherService("Photograher")->first();
                $currentServiceId = $user->otherService("Photographer")->first()->id ?? null;
                break;

            case 'videographer':
                $service = $user->otherService("Videographer")->first();
                $currentServiceId = $user->otherService("Videographer")->first()->id ?? null;
                break;

            case 'designer':
                $service = $user->otherService("Designer")->first();
                $currentServiceId = $user->otherService("Designer")->first()->id ?? null;
                break;

            default:
                return null;
        }

        return view('admin.dashboards.new-user', [
            'userId' => $this->getUserId(),
            'dashboardType' => $dashboardType,
            'service' => $service,
            'currentServiceId' => $currentServiceId,
            'modules' => $modules,
        ]);
    }

    public function searchUsers($dashboardType, Request $request)
    {
        $modules = collect(session('modules', []));
        $user = Auth::user();
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json([]);
        }

        $serviceId = null;
        $serviceType = null;
        if ($dashboardType == 'promoter') {
            $serviceType = 'App\Models\Promoter';
            $serviceId = $user->promoters->first()->id ?? null;
        } elseif ($dashboardType == 'band') {
            $serviceType = 'App\Models\OtherService';
            $serviceId = $user->otherService("Band")->first()->id ?? null;
        } elseif ($dashboardType == 'designer') {
            $serviceType = 'App\Models\OtherService';
            $serviceId = $user->otherService("Designer")->first()->id ?? null;
        } elseif ($dashboardType == 'photographer') {
            $serviceType = 'App\Models\OtherService';
            $serviceId = $user->otherService("Photographer")->first()->id ?? null;
        } elseif ($dashboardType == 'videographer') {
            $serviceType = 'App\Models\OtherService';
            $serviceId = $user->otherService("Videographer")->first()->id ?? null;
        } elseif ($dashboardType == 'venue') {
            $serviceType = 'App\Models\Venue';
            $serviceId = $user->venues->first()->id ?? null;
        }

        $linkedUserIds = ServiceUser::where('serviceable_id', $serviceId)
            ->where('serviceable_type', $serviceType)
            ->pluck('user_id')
            ->toArray();

        $users = User::where(function ($queryBuilder) use ($query) {
            $queryBuilder->where('first_name', 'LIKE', "%{$query}%")
                ->orWhere('last_name', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%");
        })
            ->whereNotIn('id', $linkedUserIds)
            ->get();

        $result = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
            ];
        });

        return response()->json([
            'userId' => $this->getUserId(),
            'dashboardType' => $dashboardType,
            'result' => $result,
            'modules' => $modules,
        ]);
    }

    public function linkUser($dashboardType, $id, Request $request)
    {
        $modules = collect(session('modules', []));
        try {
            $userId = User::where('id', $id)->value('id');
            if (!$userId) {
                return response()->json(['message' => 'User not found.'], 404);
            }

            $currentServiceId = $request->currentServiceId;
            $role = 'standard';

            if ($dashboardType == 'promoter') {
                $serviceType = Promoter::class;
            } elseif ($dashboardType == 'venue') {
                $serviceType = Venue::class;
            } elseif (in_array($dashboardType, ['band', 'designer', 'photographer', 'videographer'])) {
                $serviceType = OtherService::class;
            } else {
                return response()->json(['message' => 'Invalid dashboard type.'], 400);
            }

            ServiceUser::insert([
                'user_id' => $userId,
                'serviceable_id' => $currentServiceId,
                'serviceable_type' => $serviceType,
                'role' => $role,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['message' => 'User successfully added.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while linking the user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function deleteUser(Request $request)
    {
        $modules = collect(session('modules', []));
        $relatedUser = DB::table('service_user')
            ->where('user_id', $request->user_id)
            ->where('serviceable_id', $request->service_id)
            ->first();

        if (!$relatedUser) {
            return response()->json(['success' => false, 'message' => 'Could not find user.']);
        }

        DB::table('service_user')
            ->where('user_id', $request->user_id)
            ->where('serviceable_id', $request->service_id)
            ->delete();

        return response()->json(['success' => true, 'message' => 'User successfully removed.']);
    }
}