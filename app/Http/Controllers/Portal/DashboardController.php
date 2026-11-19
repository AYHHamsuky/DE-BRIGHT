<?php

namespace App\Http\Controllers\Portal;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Services\Paystack;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, Paystack $paystack)
    {
        $student = $request->user()->student()->with('course')->firstOrFail();
        $statement = $student->feeStatement();

        return view('portal.dashboard', [
            'student' => $student,
            'statement' => $statement,
            'totalDue' => $statement->sum('balance'),
            'totalPaid' => (int) $student->payments()->where('status', PaymentStatus::Successful)->sum('amount'),
            'recentPayments' => $student->payments()->with('fee')->latest()->take(5)->get(),
            'onlinePaymentReady' => $paystack->isConfigured(),
        ]);
    }
}
