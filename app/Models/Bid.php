<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_id',
        'user_id',
        'amount',
        'status',
        'is_autobid',
        'max_autobid_amount',
        'ip_address'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'max_autobid_amount' => 'decimal:2',
        'is_autobid' => 'boolean',
    ];

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->auction->product();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeWon($query)
    {
        return $query->where('status', 'won');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->latest()->limit($limit);
    }

    public function isWinning()
    {
        return $this->status === 'won';
    }

    public function isOutbid()
    {
        return $this->status === 'outbid';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isHighestBid()
    {
        $highestBid = $this->auction->getHighestBid();
        return $highestBid && $highestBid->id === $this->id;
    }
}