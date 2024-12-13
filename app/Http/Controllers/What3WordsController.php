<?php

namespace App\Http\Controllers;

use App\Services\What3WordsService;
use Illuminate\Http\Request;

class What3WordsController extends Controller
{
    protected $what3wordsService;

    public function __construct(What3WordsService $what3wordsService)
    {
        $this->what3wordsService = $what3wordsService;
    }

    /**
     * Handle the What3Words address input and get suggestions.
     */
    public function suggest(Request $request)
    {
        $validated = $request->validate([
            'w3w' => 'required|string|min:7',
        ]);

        $address = $validated['w3w'];

        // Trigger the What3Words service to get suggestions
        $suggestedWords = $this->what3wordsService->getSuggestedWords($address);

        if ($suggestedWords) {
            return response()->json(['success' => true, 'suggestions' => $suggestedWords]);
        }

        return response()->json(['success' => false, 'suggestions' => 'No suggestions avaliable.']);
    }
}