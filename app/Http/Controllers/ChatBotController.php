<?php

namespace App\Http\Controllers;

use App\Models\BusinessOwner;
use App\Models\ChatBot;
use App\Models\CustomerDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as IPREQUEST;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;


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
        return view('chatbot.index', compact('business'));
    }

    public function getNewChatBot($slug)
    {
        Session::put('locale', 'tr');
        App::setLocale('tr');
        // fodays-coffee
        $business = BusinessOwner::where('slug', $slug)->first(['id', 'business_name','google_review']);
        $userIp = IPREQUEST::ip();
        $lastScan = Session::get('last_scan_at_' . $userIp);

        if (!$lastScan || Carbon::parse($lastScan)->diffInDays(Carbon::now()) >= 2) {
            // It's been 2 days or more, so we can increment the scan count
            $business->increment('qr_scan_count');

            // Store the new scan time in the session
            Session::put('last_scan_at_' . $userIp, Carbon::now());
        }
        return view('chatbot.new.custom', compact('business'));
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

    public function saveCustomerData(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'business_owner_id' => 'required|exists:business_owners,id',
        ]);

        // Create a new customer record
        $customer = CustomerDetail::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'business_owner_id' => $request->business_owner_id,
        ]);

        // Return a response indicating success
        return response()->json(['success' => true, 'customer' => $customer]);
    }
}
