<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DatabaseController extends Controller
{
    public function index()
    {
        // Tampilkan halaman backup & restore
        $backups = Storage::files('backups');
        return view('admin.database.index', compact('backups'));
    }

    public function backup()
    {
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $timestamp = now()->format('Ymd_His');
        $fileName = "backup_{$timestamp}.sql";
        $filePath = storage_path("app/backups/{$fileName}");

        // Buat folder jika belum ada
        if(!File::exists(storage_path('app/backups'))) {
            File::makeDirectory(storage_path('app/backups'), 0755, true);
        }

        // Gunakan mysqldump
        $command = "mysqldump --user={$dbUser} --password={$dbPass} {$dbName} > {$filePath}";
        $returnVar = NULL;
        $output = NULL;
        exec($command, $output, $returnVar);

        if($returnVar !== 0) {
            return back()->with('error', 'Backup gagal! Pastikan mysqldump tersedia di server.');
        }

        return back()->with('success', "Backup berhasil! File: {$fileName}");
    }

    // Restore database via web form (DB::unprepared)
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,txt'
        ]);

        $file = $request->file('backup_file');
        $filePath = $file->getRealPath();

        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');

        $command = "mysql --user={$dbUser} --password={$dbPass} {$dbName} < {$filePath}";
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            return back()->with('error', 'Restore gagal! Pastikan mysql CLI tersedia di server.');
        }
        return back()->with('success', 'Restore berhasil!');
            }

    public function download($fileName)
    {
        $filePath = "backups/{$fileName}";

        if(!Storage::exists($filePath)) {
            return back()->with('error', 'File backup tidak ditemukan.');
        }

        return Storage::download($filePath);
    }
}
