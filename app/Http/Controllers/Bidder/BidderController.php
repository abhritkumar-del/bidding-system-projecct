<?php

namespace App\Http\Controllers\Bidder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BidderController extends Controller
{
    // Constructor removed - Middleware handles authentication

    public function dashboard()
    {
        $user = auth()->user();
        
        // No need to check isBidder() - middleware handles it

        $stats = [
            'total_bids' => $user->bids()->count(),
            'active_bids' => $user->bids()->where('status', 'active')->count(),
            'won_auctions' => $user->wonAuctions()->count(),
            'pending_payments' => $user->payments()->pending()->count(),
        ];

        $activeBids = $user->bids()
            ->with(['auction.product.category'])
            ->where('status', 'active')
            ->latest()
            ->limit(5)
            ->get();

        $wonAuctions = $user->wonAuctions()
            ->with(['product.category', 'payment'])
            ->latest()
            ->limit(5)
            ->get();

        return view('bidder.dashboard', compact('stats', 'activeBids', 'wonAuctions'));
    }

    public function myBids()
    {
        $bids = auth()->user()->bids()
            ->with(['auction.product.category'])
            ->latest()
            ->paginate(20);

        return view('bidder.bids.index', compact('bids'));
    }

    public function wonAuctions()
    {
        $wonAuctions = auth()->user()->wonAuctions()
            ->with(['product.category', 'payment'])
            ->latest()
            ->paginate(20);

        return view('bidder.won-auctions', compact('wonAuctions'));
    }
}