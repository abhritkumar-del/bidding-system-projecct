<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use Carbon\Carbon;

class CleanExpiredAuctions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auction:clean-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up auctions that were scheduled but never started';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleaning expired scheduled auctions...');

        $now = Carbon::now();

        // Find scheduled auctions where end_time has already passed
        // This means the auction was never activated
        $expiredAuctions = Auction::where('status', 'scheduled')
            ->where('end_time', '<', $now)
            ->get();

        $cleanedCount = 0;

        foreach ($expiredAuctions as $auction) {
            // Cancel the auction
            $auction->update(['status' => 'cancelled']);
            
            // Update product status back to approved
            $auction->product->update(['status' => 'approved']);

            $cleanedCount++;
            
            $this->info("✓ Cancelled expired Auction #{$auction->id} - Product: {$auction->product->name}");
        }

        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("✓ Cleaned {$cleanedCount} expired auctions!");

        return Command::SUCCESS;
    }
}