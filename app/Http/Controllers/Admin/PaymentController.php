<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['user', 'auction.product'])
            ->latest()
            ->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    public function approve(Payment $payment)
    {
        if (!$payment->isPending()) {
            return back()->with('error', 'Payment already processed!');
        }

        $payment->approve(auth()->id());

        return back()->with('success', 'Payment approved successfully!');
    }

    public function reject(Request $request, Payment $payment)
    {
        $request->validate(['reason' => 'required|string']);

        if (!$payment->isPending()) {
            return back()->with('error', 'Payment already processed!');
        }

        $payment->reject($request->reason, auth()->id());

        return back()->with('success', 'Payment rejected!');
    }
}
