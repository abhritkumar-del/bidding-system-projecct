<?php

namespace App\Http\Controllers\Bidder;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Http\Request;

class BidController extends Controller
{
    // Constructor removed - Middleware handles authentication

    public function create(Auction $auction)
    {
        // No need to check isBidder() - middleware handles it

        if (!$auction->isActive()) {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'This auction is not active!');
        }

        // Check if user is bidding on their own product
        if ($auction->product->user_id === auth()->id()) {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'You cannot bid on your own product!');
        }

        $minimumBid = $auction->getMinimumBidAmount();
        
        return view('bidder.bids.create', compact('auction', 'minimumBid'));
    }

    public function store(Request $request, Auction $auction)
    {
        // No need to check isBidder() - middleware handles it

        if (!$auction->isActive()) {
            return back()->with('error', 'Auction is not active!');
        }

        // Check if user is bidding on their own product
        if ($auction->product->user_id === auth()->id()) {
            return back()->with('error', 'You cannot bid on your own product!');
        }

        $minimumBid = $auction->getMinimumBidAmount();

        $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:' . $minimumBid,
            ],
        ], [
            'amount.min' => 'Minimum bid amount is ৳' . number_format($minimumBid, 2),
        ]);

        // Update previous bids status
        $auction->bids()
            ->where('status', 'active')
            ->update(['status' => 'outbid']);

        // Create new bid
        Bid::create([
            'auction_id' => $auction->id,
            'user_id' => auth()->id(),
            'amount' => $request->amount,
            'status' => 'active',
            'ip_address' => $request->ip(),
        ]);

        // Update auction
        $auction->update([
            'current_price' => $request->amount,
            'total_bids' => $auction->total_bids + 1,
        ]);

        return redirect()->route('auctions.show', $auction)
            ->with('success', 'Bid placed successfully! You are currently winning.');
    }
}