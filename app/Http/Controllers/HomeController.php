<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Featured/Active auctions
        $liveAuctions = Auction::with(['product.category', 'bids'])
            ->active()
            ->orderBy('end_time', 'asc')
            ->limit(10)
            ->get();

        // Featured products
        $featuredProducts = Product::with(['category', 'auction'])
            ->where('status', 'active')
            ->latest()
            ->limit(6)
            ->get();

        // Categories with product count
        $categories = Category::withCount(['activeProducts'])
            ->active()
            ->ordered()
            ->get();

        // Statistics
        $stats = [
            'total_auctions' => Auction::active()->count(),
            'total_products' => Product::where('status', 'active')->count(),
            'ending_soon' => Auction::active()
                ->where('end_time', '<=', now()->addHours(2))
                ->count(),
        ];

        return view('home', compact('liveAuctions', 'featuredProducts', 'categories', 'stats'));
    }

    public function dashboard()
    {
        $user = auth()->user();

        // Redirect based on role
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isSeller()) {
            return redirect()->route('seller.dashboard');
        } elseif ($user->isBidder()) {
            return redirect()->route('bidder.dashboard');
        }

        // Fallback to home if role not recognized
        return redirect()->route('home');
    }
}