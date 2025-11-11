<?php

namespace App\Http\Controllers\Bidder;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function create(Auction $auction)
    {
        if (!$auction->isActive()) {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'This auction is not active!');
        }

        return view('bidder.bids.create', compact('auction'));
    }

    public function store(Request $request, Auction $auction)
    {
        $user = auth()->user();

        if (!$user->isBidder()) {
            abort(403);
        }

        if (!$auction->isActive()) {
            return back()->with('error', 'This auction is not active!');
        }

        $minimumBid = $auction->getMinimumBidAmount();

        $request->validate([
            'amount' => 'required|numeric|min:' . $minimumBid,
        ]);

        // Check if user is the product owner
        if ($auction->product->user_id === $user->id) {
            return back()->with('error', 'You cannot bid on your own product!');
        }

        // Create bid
        Bid::create([
            'auction_id' => $auction->id,
            'user_id' => $user->id,
            'amount' => $request->amount,
            'status' => 'active',
            'ip_address' => $request->ip(),
        ]);

        // Update auction current price and total bids
        $auction->update([
            'current_price' => $request->amount,
            'total_bids' => $auction->total_bids + 1,
        ]);

        // Mark previous bids as outbid
        $auction->bids()
            ->where('user_id', '!=', $user->id)
            ->where('status', 'active')
            ->update(['status' => 'outbid']);

        return redirect()->route('auctions.show', $auction)
            ->with('success', 'Bid placed successfully!');
    }
}