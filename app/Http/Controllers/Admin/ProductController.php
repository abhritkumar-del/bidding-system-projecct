<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['user', 'category', 'auction'])
            ->latest()
            ->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load(['user', 'category', 'auction.bids']);
        return view('admin.products.show', compact('product'));
    }

    public function approve(Product $product)
    {
        $product->update(['status' => 'approved']);
        return back()->with('success', 'Product approved!');
    }

    public function reject(Request $request, Product $product)
    {
        $request->validate(['reason' => 'required|string']);
        
        $product->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason
        ]);

        return back()->with('success', 'Product rejected!');
    }

    public function destroy(Product $product)
    {
        if ($product->auction && $product->auction->bids()->count() > 0) {
            return back()->with('error', 'Cannot delete product with bids!');
        }

        $product->delete();
        return back()->with('success', 'Product deleted!');
    }
}

// ============================================
// Admin AuctionController
// app/Http/Controllers/Admin/AuctionController.php
// ============================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function index()
    {
        $auctions = Auction::with(['product', 'winner'])
            ->latest()
            ->paginate(20);

        return view('admin.auctions.index', compact('auctions'));
    }

    public function show(Auction $auction)
    {
        $auction->load(['product.user', 'bids.user', 'winner']);
        return view('admin.auctions.show', compact('auction'));
    }

    public function cancel(Auction $auction)
    {
        if ($auction->hasEnded()) {
            return back()->with('error', 'Cannot cancel ended auction!');
        }

        $auction->update(['status' => 'cancelled']);
        $auction->bids()->update(['status' => 'lost']);

        return back()->with('success', 'Auction cancelled!');
    }
}

// ============================================
// Admin PaymentController
// app/Http/Controllers/Admin/PaymentController.php
// ============================================

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