<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AuctionController extends Controller
{
    // Constructor removed - Middleware handles authentication

    public function index()
    {
        $user = auth()->user();
        
        // No need to check isSeller() - middleware handles it

        $auctions = Auction::with(['product'])
            ->whereHas('product', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->paginate(20);

        return view('seller.auctions.index', compact('auctions'));
    }

    public function create()
    {
        $user = auth()->user();

        // Get approved products without active auctions
        $products = Product::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereDoesntHave('auction', function($q) {
                $q->where('status', 'active');
            })
            ->get();

        return view('seller.auctions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'bid_increment' => 'required|numeric|min:1',
        ]);

        // Verify product ownership
        $product = Product::findOrFail($request->product_id);
        
        if ($product->user_id !== $user->id) {
            abort(403, 'Unauthorized. This product does not belong to you.');
        }

        if ($product->status !== 'approved') {
            return back()->with('error', 'Only approved products can be auctioned!');
        }

        // Check if product already has active auction
        if ($product->auction && $product->auction->isActive()) {
            return back()->with('error', 'Product already has an active auction!');
        }

        Auction::create([
            'product_id' => $product->id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'current_price' => $product->starting_price,
            'bid_increment' => $request->bid_increment,
            'status' => 'scheduled',
        ]);

        // Update product status
        $product->update(['status' => 'active']);

        return redirect()->route('seller.auctions.index')
            ->with('success', 'Auction created successfully!');
    }

    public function show(Auction $auction)
    {
        $user = auth()->user();

        // Verify ownership
        if ($auction->product->user_id !== $user->id) {
            abort(403, 'Unauthorized. This auction does not belong to you.');
        }

        $auction->load(['product.category', 'bids.user']);

        return view('seller.auctions.show', compact('auction'));
    }
}