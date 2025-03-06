<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateFileHashes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:file-hashes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and save the hashes of important files to detect tampering';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $files = [
            'app',
            'routes/console.php',
            'bootstrap/app.php',
        ];

        $hashes = [];

        foreach ($files as $file) {
            $fullPath = base_path($file);

            if (is_dir($fullPath)) {
                $dirFiles = File::allFiles($fullPath);
                foreach ($dirFiles as $dirFile) {
                    $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $dirFile->getRealPath());
                    $hashes[$relativePath] = md5_file($dirFile->getRealPath());
                }
            } elseif (File::exists($fullPath)) {
                $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $fullPath);
                $hashes[$relativePath] = md5_file($fullPath);
            }
        }

        File::put(storage_path('file_hashes.json'), json_encode($hashes, JSON_PRETTY_PRINT));

        $this->info('File hashes generated and saved successfully.');
    }
}
