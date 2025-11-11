<?php

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
