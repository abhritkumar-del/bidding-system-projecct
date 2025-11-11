<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'slug',
        'description',
        'starting_price',
        'reserve_price',
        'buy_now_price',
        'images',
        'condition',
        'status',
        'rejection_reason',
        'views'
    ];

    protected $casts = [
        'images' => 'array',
        'starting_price' => 'decimal:2',
        'reserve_price' => 'decimal:2',
        'buy_now_price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name) . '-' . Str::random(6);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function auction()
    {
        return $this->hasOne(Auction::class);
    }

    public function bids()
    {
        return $this->hasManyThrough(Bid::class, Auction::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFeatured($query)
    {
        return $query->where('status', 'active')
                    ->whereHas('auction', function($q) {
                        $q->where('status', 'active');
                    })
                    ->latest()
                    ->limit(6);
    }

    public function hasActiveAuction()
    {
        return $this->auction && $this->auction->status === 'active';
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    public function getFirstImage()
    {
        if ($this->images && count($this->images) > 0) {
            return $this->images[0];
        }
        return 'placeholder.jpg';
    }
}