<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = auth()->user()->products()
            ->with('category')
            ->latest()
            ->paginate(12);

        return view('seller.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::active()->ordered()->get();
        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'starting_price' => 'required|numeric|min:1',
            'reserve_price' => 'nullable|numeric|min:' . $request->starting_price,
            'buy_now_price' => 'nullable|numeric|min:' . $request->starting_price,
            'condition' => 'required|in:new,used,refurbished',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
        }

        Product::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'starting_price' => $request->starting_price,
            'reserve_price' => $request->reserve_price,
            'buy_now_price' => $request->buy_now_price,
            'condition' => $request->condition,
            'images' => $images,
            'status' => 'pending',
        ]);

        return redirect()->route('seller.products.index')
            ->with('success', 'Product created! Waiting for admin approval.');
    }

    public function edit(Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            abort(403);
        }

        $categories = Category::active()->ordered()->get();
        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            abort(403);
        }

        if ($product->status === 'sold' || ($product->auction && $product->auction->isActive())) {
            return back()->with('error', 'Cannot edit active or sold products!');
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'starting_price' => 'required|numeric|min:1',
            'reserve_price' => 'nullable|numeric|min:' . $request->starting_price,
            'buy_now_price' => 'nullable|numeric|min:' . $request->starting_price,
            'condition' => 'required|in:new,used,refurbished',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $images = $product->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
        }

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'starting_price' => $request->starting_price,
            'reserve_price' => $request->reserve_price,
            'buy_now_price' => $request->buy_now_price,
            'condition' => $request->condition,
            'images' => $images,
            'status' => 'pending',
        ]);

        return redirect()->route('seller.products.index')
            ->with('success', 'Product updated!');
    }

    public function destroy(Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            abort(403);
        }

        if ($product->auction && $product->auction->bids()->count() > 0) {
            return back()->with('error', 'Cannot delete product with bids!');
        }

        $product->delete();
        return back()->with('success', 'Product deleted!');
    }
}
