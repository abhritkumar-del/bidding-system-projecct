<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'start_time',
        'end_time',
        'current_price',
        'bid_increment',
        'status',
        'winner_id',
        'total_bids',
        'reserve_met'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'current_price' => 'decimal:2',
        'bid_increment' => 'decimal:2',
        'reserve_met' => 'boolean',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('start_time', '<=', now())
                    ->where('end_time', '>', now());
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('start_time', '>', now());
    }

    public function scopeEnded($query)
    {
        return $query->where('status', 'ended')
                    ->orWhere('end_time', '<=', now());
    }

    // Status Check Methods
    public function isActive()
    {
        return $this->status === 'active' 
            && $this->start_time <= now() 
            && $this->end_time > now();
    }

    public function isScheduled()
    {
        return $this->status === 'scheduled' && $this->start_time > now();
    }

    public function hasEnded()
    {
        return $this->status === 'ended' || $this->end_time <= now();
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    // Time Methods
    public function getTimeRemaining()
    {
        if ($this->hasEnded()) {
            return 'Ended';
        }
        
        if ($this->isScheduled()) {
            return 'Starts ' . $this->start_time->diffForHumans();
        }

        $now = now();
        $diff = $this->end_time->diff($now);
        
        if ($diff->d > 0) {
            return $diff->d . 'd ' . $diff->h . 'h';
        } elseif ($diff->h > 0) {
            return $diff->h . 'h ' . $diff->i . 'm';
        } else {
            return $diff->i . 'm ' . $diff->s . 's';
        }
    }

    public function getRemainingSeconds()
    {
        if ($this->hasEnded()) {
            return 0;
        }
        return max(0, $this->end_time->timestamp - now()->timestamp);
    }

    public function getDurationInDays()
    {
        return $this->start_time->diffInDays($this->end_time);
    }

    public function getProgressPercentage()
    {
        if ($this->hasEnded()) {
            return 100;
        }

        if ($this->isScheduled()) {
            return 0;
        }

        $totalDuration = $this->start_time->diffInSeconds($this->end_time);
        $elapsed = $this->start_time->diffInSeconds(now());
        
        return min(100, round(($elapsed / $totalDuration) * 100, 2));
    }

    // Bid Methods
    public function getHighestBid()
    {
        return $this->bids()->orderBy('amount', 'desc')->first();
    }

    public function getMinimumBidAmount()
    {
        $highestBid = $this->getHighestBid();
        if ($highestBid) {
            return $highestBid->amount + $this->bid_increment;
        }
        return $this->product->starting_price;
    }

    public function hasMetReservePrice()
    {
        if (!$this->product->reserve_price) {
            return true;
        }
        
        $highestBid = $this->getHighestBid();
        if (!$highestBid) {
            return false;
        }
        
        return $highestBid->amount >= $this->product->reserve_price;
    }

    // Status Update Methods
    public function updateStatus()
    {
        $now = now();

        if ($this->status === 'cancelled') {
            return;
        }

        if ($this->start_time > $now && $this->status !== 'scheduled') {
            $this->update(['status' => 'scheduled']);
        } elseif ($this->start_time <= $now && $this->end_time > $now && $this->status !== 'active') {
            $this->update(['status' => 'active']);
        } elseif ($this->end_time <= $now && $this->status !== 'ended') {
            $this->endAuction();
        }
    }

    public function endAuction()
    {
        $highestBid = $this->getHighestBid();
        
        $updateData = ['status' => 'ended'];
        
        if ($highestBid && $this->hasMetReservePrice()) {
            $updateData['winner_id'] = $highestBid->user_id;
            $updateData['reserve_met'] = true;
            
            // Mark highest bid as won
            $highestBid->update(['status' => 'won']);
            
            // Mark all other bids as lost
            $this->bids()->where('id', '!=', $highestBid->id)->update(['status' => 'lost']);
            
            // Update product status
            $this->product->update(['status' => 'sold']);
        } else {
            // No winner or reserve not met
            $updateData['reserve_met'] = false;
            
            // Mark all bids as lost
            $this->bids()->update(['status' => 'lost']);
            
            // Return product to approved status if no winner
            $this->product->update(['status' => 'approved']);
        }
        
        $this->update($updateData);
    }

    // Cancel Auction
    public function cancel()
    {
        if ($this->hasEnded()) {
            return false;
        }

        $this->update(['status' => 'cancelled']);
        $this->bids()->update(['status' => 'lost']);
        $this->product->update(['status' => 'approved']);

        return true;
    }

    // Statistics
    public function getTotalBidValue()
    {
        return $this->bids()->sum('amount');
    }

    public function getUniqueBidders()
    {
        return $this->bids()->distinct('user_id')->count('user_id');
    }

    public function getAverageBidAmount()
    {
        return $this->bids()->avg('amount') ?? 0;
    }
}