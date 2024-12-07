<?php

namespace App\Http\Controllers;

use App\Models\BusinessOwner;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = Package::all();
        // Attach the total number of business owners using each package
        $packages->map(function ($package) {
            $package->total_used = BusinessOwner::where('package', $package->id)->count();
            return $package;
        });
        return view("admin.packages.index", compact("packages"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'customers' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:100',

        ]);

        // Create a new package
        $package = new Package();
        $package->name = $validatedData['name'];
        $package->customers = $validatedData['customers'];
        $package->price = $validatedData['price'];
        $package->quantity = $validatedData['quantity'];
        $package->save(); // Save to the database

        // Redirect with a success message
        return redirect()->route('admin.packages.index')->with('success', 'Package created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'customers' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:100',
        ]);

        // Update the package
        $package->name = $validatedData['name'];
        $package->customers = $validatedData['customers'];
        $package->price = $validatedData['price'];
        $package->quantity = $validatedData['quantity'];
        $package->save(); // Save the updated package

        // Redirect with a success message
        return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        // Delete the package
        $package->delete();

        // Redirect with a success message
        return redirect()->route('admin.packages.index')->with('success', 'Package deleted successfully!');
    }
}
