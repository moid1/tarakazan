<?php

namespace App\Http\Controllers;

use App\Models\CustomerDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = CustomerDetail::where('is_verified', true)->with('redeemCoupon')->get();
        return view('business_owners.customers.index', compact('customers'));
    }

    public function exportPdf()
    {
        // Fetch all business owners
        $customers = CustomerDetail::all();

        // Generate the PDF
        $pdf = Pdf::loadView('business_owners.customers.pdf', compact('customers'));

        // Return the PDF for download
        return $pdf->download('customers.pdf');
    }

    public function toggleBlockUser(Request $request)
    {
        $blockUserId = $request->block_id;
        $authUser = auth()->user();
    
        if ($authUser->user_type === 'admin') {
            $userToBlock = User::findOrFail($blockUserId);
    
            // Toggle the block status
            $userToBlock->is_block = !$userToBlock->is_block;
            $userToBlock->save();
    
            // Return a success message or redirect
            return redirect()->back()->with('success', $userToBlock->is_block ? 'User has been blocked.' : 'User has been unblocked.');
        } else {
            return response()->json(['error' => 'You are not authorized to block users.'], 403);
        }
    }
    

}
