<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckStorageLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if public/storage is a valid filesystem link pointing to storage/app/public';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $publicStorage = public_path('storage');
        $targetStorage = storage_path('app/public');

        $this->info("Checking public/storage link status...");

        if (!file_exists($publicStorage)) {
            $this->error("FAIL: public/storage is MISSING.");
            $this->printRepairInstructions();
            return 1;
        }

        // is_link() often fails on Windows for Directory Junctions, so we test if readlink works
        $linkTarget = @readlink($publicStorage);
        
        if ($linkTarget === false && !is_link($publicStorage)) {
            $this->error("FAIL: public/storage exists but is a REGULAR FOLDER, not a valid symlink/junction.");
            $this->printRepairInstructions();
            return 1;
        }

        // On Windows, readlink might return a slightly different path format, so we normalize both
        $normalizedLink = str_replace('\\', '/', realpath($linkTarget ?: $publicStorage));
        $normalizedTarget = str_replace('\\', '/', realpath($targetStorage));

        if ($normalizedLink !== $normalizedTarget) {
            $this->error("FAIL: public/storage is a link, but it points to the WRONG target.");
            $this->line("Expected: " . $normalizedTarget);
            $this->line("Actual:   " . $normalizedLink);
            $this->printRepairInstructions();
            return 1;
        }

        $this->info("PASS: public/storage is a valid link pointing to storage/app/public.");
        return 0;
    }

    private function printRepairInstructions()
    {
        $this->line("");
        $this->warn("HOW TO FIX THIS PERMANENTLY (On Windows):");
        $this->line("1. Stop all local servers (npm run dev, php artisan serve) to unlock the folder.");
        $this->line("2. Run the following command in your terminal to delete the fake folder:");
        $this->info("   rmdir /S /Q public\storage");
        $this->line("3. Run the official Laravel storage link command:");
        $this->info("   php artisan storage:link");
        $this->line("");
    }
}
