<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Auction;
use App\Models\Payment;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Constructor removed - Middleware handles authentication

    public function dashboard()
    {
        // No need to check isAdmin() - middleware handles it
        
        $stats = [
            'total_users' => User::count(),
            'total_sellers' => User::sellers()->count(),
            'total_bidders' => User::bidders()->count(),
            'pending_users' => User::where('status', 'pending')->count(),
            'banned_users' => User::where('status', 'banned')->count(),
            
            'total_categories' => Category::count(),
            'active_categories' => Category::active()->count(),
            
            'total_products' => Product::count(),
            'pending_products' => Product::pending()->count(),
            'approved_products' => Product::approved()->count(),
            'active_products' => Product::active()->count(),
            'sold_products' => Product::where('status', 'sold')->count(),
            
            'total_auctions' => Auction::count(),
            'active_auctions' => Auction::active()->count(),
            'scheduled_auctions' => Auction::scheduled()->count(),
            'ended_auctions' => Auction::ended()->count(),
            
            'total_payments' => Payment::count(),
            'pending_payments' => Payment::pending()->count(),
            'completed_payments' => Payment::completed()->count(),
            'total_revenue' => Payment::completed()->sum('amount'),
        ];

        // Recent activities
        $recentUsers = User::latest()->limit(5)->get();
        $recentProducts = Product::with(['user', 'category'])->latest()->limit(5)->get();
        $recentAuctions = Auction::with(['product'])->latest()->limit(5)->get();
        $pendingPayments = Payment::with(['user', 'auction.product'])
            ->pending()
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'recentProducts',
            'recentAuctions',
            'pendingPayments'
        ));
    }

    // User Management
    public function users()
    {
        $users = User::withCount(['products', 'bids', 'wonAuctions'])
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'active']);

        return back()->with('success', 'User approved successfully!');
    }

    public function banUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot ban admin user!');
        }

        $user->update(['status' => 'banned']);

        return back()->with('success', 'User banned successfully!');
    }

    public function unbanUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'active']);

        return back()->with('success', 'User unbanned successfully!');
    }
}