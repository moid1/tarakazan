<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $campaigns = Campaign::where('user_id', \Auth::id())->with(['coupons','sms'])->latest()->get();
        // dd($campaigns);
        return view("business_owners.campaigns.index", compact("campaigns"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('business_owners.campaigns.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:campaigns,name',
            'description' => 'required',
        ]);
        $validatedData['user_id'] = auth()->id();
        Campaign::create($validatedData);
        return redirect()->route('campaign.index')->with('success', 'Campaign created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Campaign $campaign)
    {
        //
    }

    public function edit($id)
    {
        // Find the campaign by ID
        $campaign = Campaign::findOrFail($id);
        return view('business_owners.campaigns.edit', compact('campaign'));
    }
    public function update(Request $request, $id)
    {
        // Validate the form inputs
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);

        // Find the campaign by ID
        $campaign = Campaign::findOrFail($id);

        // Update the campaign data
        $campaign->name = $validated['name'];
        $campaign->description = $validated['description'];
        $campaign->save();

        // Redirect to a campaign list page or a success page
        return redirect()->route('campaign.index')->with('success', 'Campaign updated successfully!');
    }

    public function destroy($id)
    {
        // Find the campaign by ID
        $campaign = Campaign::findOrFail($id);

        // Delete the campaign
        $campaign->delete();

        // Redirect to the campaigns index page with a success message
        return redirect()->route('campaign.index')->with('success', 'Campaign deleted successfully!');
    }
}
