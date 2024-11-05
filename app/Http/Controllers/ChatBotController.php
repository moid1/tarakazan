<?php

namespace App\Http\Controllers;

use App\Models\BusinessOwner;
use App\Models\ChatBot;
use Illuminate\Http\Request;

class ChatBotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    // ChatbotController.php

    public function show($slug)
    {
        $business = BusinessOwner::where('slug', $slug)->firstOrFail();

        // Hardcoded questions
        $questions = [
            'What is your favorite color?',
            'What is your preferred contact method?',
            'Do you need any assistance with our services?'
        ];

        return view('chatbot.index', compact('business', 'questions'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChatBot $chatBot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChatBot $chatBot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatBot $chatBot)
    {
        //
    }
}
