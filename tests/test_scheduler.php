<?php

/**
 * Quick Scheduler Test Script
 * Run this to verify your scheduler setup
 * 
 * Usage: php test_scheduler.php
 */

echo "\n";
echo "╔═══════════════════════════════════════╗\n";
echo "║  Deshi Bid - Scheduler Test Script   ║\n";
echo "╚═══════════════════════════════════════╝\n";
echo "\n";

// Change to project directory
chdir(__DIR__);

echo "📂 Current Directory: " . getcwd() . "\n\n";

// Test 1: Check if artisan exists
echo "Test 1: Checking artisan file...\n";
if (file_exists('artisan')) {
    echo "   ✅ artisan file found\n\n";
} else {
    echo "   ❌ artisan file NOT found!\n";
    echo "   Please run this script from project root\n\n";
    exit(1);
}

// Test 2: List scheduled tasks
echo "Test 2: Listing scheduled tasks...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
exec('php artisan schedule:list', $output, $return);
foreach ($output as $line) {
    echo "   " . $line . "\n";
}
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Test 3: Test Update Auction Status
echo "Test 3: Testing auction:update-status command...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
exec('php artisan auction:update-status 2>&1', $output1, $return1);
foreach ($output1 as $line) {
    echo "   " . $line . "\n";
}
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Test 4: Test Process Winners
echo "Test 4: Testing auction:process-winners command...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
exec('php artisan auction:process-winners 2>&1', $output2, $return2);
foreach ($output2 as $line) {
    echo "   " . $line . "\n";
}
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Test 5: Test Clean Expired
echo "Test 5: Testing auction:clean-expired command...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
exec('php artisan auction:clean-expired 2>&1', $output3, $return3);
foreach ($output3 as $line) {
    echo "   " . $line . "\n";
}
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Summary
echo "╔═══════════════════════════════════════╗\n";
echo "║           Test Summary                ║\n";
echo "╚═══════════════════════════════════════╝\n";

$totalTests = 5;
$passedTests = 0;

if (file_exists('artisan')) $passedTests++;
if ($return == 0) $passedTests++;
if ($return1 == 0) $passedTests++;
if ($return2 == 0) $passedTests++;
if ($return3 == 0) $passedTests++;

echo "\n";
echo "Tests Passed: {$passedTests}/{$totalTests}\n";

if ($passedTests == $totalTests) {
    echo "\n✅ ALL TESTS PASSED! Scheduler is working correctly.\n";
    echo "\n📝 Next Steps:\n";
    echo "   1. Set up cron job (see CRON_SETUP.md)\n";
    echo "   2. Create test auction to verify automatic updates\n";
    echo "   3. Monitor logs: tail -f storage/logs/laravel.log\n";
} else {
    echo "\n❌ SOME TESTS FAILED! Please check the errors above.\n";
    echo "\n🔍 Troubleshooting:\n";
    echo "   1. Run: php artisan list | grep auction\n";
    echo "   2. Check: app/Console/Commands/ directory\n";
    echo "   3. Verify: app/Console/Kernel.php schedule method\n";
}

echo "\n";