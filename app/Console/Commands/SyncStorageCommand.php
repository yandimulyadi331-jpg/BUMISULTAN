<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SyncStorageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:sync';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Sync storage/app/public to public/storage folder (Windows compatibility fix)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $source = storage_path('app/public');
        $destination = public_path('storage');

        // Create destination if not exists
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        // Get all directories
        $directories = File::directories($source);

        foreach ($directories as $dir) {
            $dirName = basename($dir);
            $destDir = $destination . '/' . $dirName;

            // Create subdirectory
            if (!File::exists($destDir)) {
                File::makeDirectory($destDir, 0755, true);
            }

            // Copy files
            $files = File::files($dir);
            foreach ($files as $file) {
                $fileName = $file->getFilename();
                $destFile = $destDir . '/' . $fileName;
                File::copy($file->getPathname(), $destFile);
            }

            $this->info("Synced: $dirName");
        }

        $this->info('✅ Storage sync completed successfully!');
    }
}
