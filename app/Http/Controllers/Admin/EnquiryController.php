<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;

class EnquiryController extends Controller
{
    public function index()
    {
        return view('admin.enquiries.index', [
            'enquiries' => Enquiry::with('course')->latest()->simplePaginate(20),
        ]);
    }

    public function show(Enquiry $enquiry)
    {
        if (! $enquiry->is_read) {
            $enquiry->update(['is_read' => true]);
        }

        return view('admin.enquiries.show', ['enquiry' => $enquiry->load('course')]);
    }

    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();

        return redirect()->route('admin.enquiries.index')->with('success', 'Enquiry deleted.');
    }
}
