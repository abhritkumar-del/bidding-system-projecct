<?php

namespace App\Http\Controllers\Bidder;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Auction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Display all payments for the bidder
     */
    public function index()
    {
        $payments = auth()->user()->payments()
            ->with(['auction.product.category'])
            ->latest()
            ->paginate(20);

        return view('bidder.payments.index', compact('payments'));
    }

    /**
     * Show payment form for a won auction
     */
    public function create(Auction $auction)
    {
        // Verify user is the winner
        if ($auction->winner_id !== auth()->id()) {
            abort(403, 'Unauthorized. You did not win this auction.');
        }

        // Check if payment already exists
        $existingPayment = Payment::where('auction_id', $auction->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingPayment && $existingPayment->isCompleted()) {
            return redirect()->route('bidder.payments.index')
                ->with('info', 'Payment already completed for this auction.');
        }

        $auction->load(['product.category']);

        return view('bidder.payments.create', compact('auction', 'existingPayment'));
    }

    /**
     * Store payment submission
     */
    public function store(Request $request, Auction $auction)
    {
        // Verify user is the winner
        if ($auction->winner_id !== auth()->id()) {
            abort(403, 'Unauthorized. You did not win this auction.');
        }

        // Validate request
        $request->validate([
            'payment_method' => 'required|in:bkash,nagad,rocket,bank,cash',
            'sender_number' => 'required_unless:payment_method,cash|string|max:20',
            'payment_proof' => 'required_unless:payment_method,cash|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'sender_number.required_unless' => 'Sender number is required for mobile payments.',
            'payment_proof.required_unless' => 'Payment screenshot is required for verification.',
        ]);

        // Check if payment already exists
        $payment = Payment::where('auction_id', $auction->id)
            ->where('user_id', auth()->id())
            ->first();

        // Handle payment proof upload
        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentProofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
        }

        if ($payment) {
            // Update existing payment
            $payment->update([
                'payment_method' => $request->payment_method,
                'sender_number' => $request->sender_number,
                'payment_proof' => $paymentProofPath ?? $payment->payment_proof,
                'status' => 'pending',
                'paid_at' => now(),
            ]);

            $message = 'Payment information updated successfully! Please wait for admin approval.';
        } else {
            // Create new payment
            Payment::create([
                'auction_id' => $auction->id,
                'user_id' => auth()->id(),
                'amount' => $auction->current_price,
                'payment_method' => $request->payment_method,
                'sender_number' => $request->sender_number,
                'payment_proof' => $paymentProofPath,
                'status' => 'pending',
                'paid_at' => now(),
            ]);

            $message = 'Payment submitted successfully! Please wait for admin approval.';
        }

        return redirect()->route('bidder.payments.index')
            ->with('success', $message);
    }

    /**
     * Show payment details
     */
    public function show(Payment $payment)
    {
        // Verify ownership
        if ($payment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        $payment->load(['auction.product.category', 'approvedBy']);

        return view('bidder.payments.show', compact('payment'));
    }
}