<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use Carbon\Carbon;

class UpdateAuctionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auction:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update auction status (scheduled -> active -> ended)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        
        $this->info('Starting auction status update...');

        // Update Scheduled -> Active
        $scheduledToActive = Auction::where('status', 'scheduled')
            ->where('start_time', '<=', $now)
            ->where('end_time', '>', $now)
            ->get();

        foreach ($scheduledToActive as $auction) {
            $auction->update(['status' => 'active']);
            $this->info("Auction #{$auction->id} changed to ACTIVE");
        }

        // Update Active -> Ended (and process winners)
        $activeToEnded = Auction::where('status', 'active')
            ->where('end_time', '<=', $now)
            ->get();

        foreach ($activeToEnded as $auction) {
            $auction->endAuction(); // This method handles winner determination
            $this->info("Auction #{$auction->id} ENDED - Winner: " . ($auction->winner_id ? "User #{$auction->winner_id}" : "No winner"));
        }

        $totalUpdated = $scheduledToActive->count() + $activeToEnded->count();
        
        $this->info("✓ Updated {$totalUpdated} auctions successfully!");
        $this->info("  - Activated: {$scheduledToActive->count()}");
        $this->info("  - Ended: {$activeToEnded->count()}");

        return Command::SUCCESS;
    }
}