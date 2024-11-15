<?php

namespace App\Http\Controllers;

use App\Models\CustomerDetail;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = CustomerDetail::all();
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
}
