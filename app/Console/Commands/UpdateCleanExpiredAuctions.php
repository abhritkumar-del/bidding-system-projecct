<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use App\Models\Payment;

class ProcessWinningBids extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auction:process-winners';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process winning bids and create payment records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing winning bids...');

        // Get ended auctions with winners but no payment record
        $endedAuctions = Auction::where('status', 'ended')
            ->whereNotNull('winner_id')
            ->whereDoesntHave('payment')
            ->with(['winner', 'product'])
            ->get();

        $processedCount = 0;

        foreach ($endedAuctions as $auction) {
            try {
                // Create payment record for winner
                Payment::create([
                    'auction_id' => $auction->id,
                    'user_id' => $auction->winner_id,
                    'amount' => $auction->current_price,
                    'payment_method' => 'cash', // Default, user will update
                    'status' => 'pending',
                ]);

                $processedCount++;
                
                $this->info("✓ Payment record created for Auction #{$auction->id} - Winner: {$auction->winner->name}");
                
            } catch (\Exception $e) {
                $this->error("✗ Failed to process Auction #{$auction->id}: " . $e->getMessage());
            }
        }

        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("✓ Processed {$processedCount} winning bids successfully!");

        return Command::SUCCESS;
    }
}