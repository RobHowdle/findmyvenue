<?php

namespace App\Http\Controllers;

use App\Models\OtherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OtherServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $otherServices = OtherService::select('services', DB::raw('COUNT(*) as count'))
            ->whereNull('deleted_at')
            ->groupBy('services')
            ->get();

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

    /**
     * Display the specified resource.
     */
    public function show(OtherService $otherService)
    {
        //
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