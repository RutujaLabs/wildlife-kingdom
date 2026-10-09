<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index()
    {
        $enquiries = Enquiry::latest()->get();

        return view('admin.enquiries.index', compact('enquiries'));
    }

    public function updateStatus(Request $request, Enquiry $enquiry)
    {
        $request->validate([
            'status' => ['required', 'in:new,reviewed,replied,closed'],
        ]);

        $enquiry->update(['status' => $request->status]);

        return back()->with('success', 'Enquiry status updated.');
    }
}
