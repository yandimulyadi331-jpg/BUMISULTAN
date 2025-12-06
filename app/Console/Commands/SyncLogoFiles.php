<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncLogoFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logo:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync existing logo files from public/logo to storage/app/public/logo';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sourceDir = public_path('logo');
        $destDir = storage_path('app/public/logo');
        $publicDestDir = public_path('storage/logo');

        // Create directories if they don't exist
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
            $this->info("Created directory: {$destDir}");
        }

        if (!is_dir($publicDestDir)) {
            mkdir($publicDestDir, 0755, true);
            $this->info("Created directory: {$publicDestDir}");
        }

        // Check if source exists
        if (!is_dir($sourceDir)) {
            $this->warn("Source directory does not exist: {$sourceDir}");
            return 0;
        }

        // Get all files from source
        $files = glob($sourceDir . '/*');
        $count = 0;

        foreach ($files as $file) {
            if (is_file($file)) {
                $filename = basename($file);
                
                // Copy to storage/app/public/logo
                $destFile = $destDir . '/' . $filename;
                if (copy($file, $destFile)) {
                    $this->info("Copied: {$filename} → storage/app/public/logo");
                    
                    // Also copy to public/storage/logo
                    $publicDestFile = $publicDestDir . '/' . $filename;
                    if (copy($file, $publicDestFile)) {
                        $this->info("Synced: {$filename} → public/storage/logo");
                        $count++;
                    }
                } else {
                    $this->error("Failed to copy: {$filename}");
                }
            }
        }

        $this->info("Sync completed! Total files: {$count}");
        return 0;
    }
}
