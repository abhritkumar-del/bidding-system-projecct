<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Auction;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    // Constructor removed - Middleware handles authentication

    public function dashboard()
    {
        $user = auth()->user();
        
        // No need to check isSeller() - middleware handles it

        $stats = [
            'total_products' => $user->products()->count(),
            'pending_products' => $user->products()->pending()->count(),
            'active_products' => $user->products()->active()->count(),
            'sold_products' => $user->products()->where('status', 'sold')->count(),
            'total_auctions' => Auction::whereHas('product', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'active_auctions' => Auction::whereHas('product', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->active()->count(),
        ];

        $recentProducts = $user->products()->with('category')->latest()->limit(5)->get();
        
        $recentAuctions = Auction::with(['product', 'bids'])
            ->whereHas('product', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->limit(5)
            ->get();

        return view('seller.dashboard', compact('stats', 'recentProducts', 'recentAuctions'));
    }
}