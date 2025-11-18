<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SystemInfoController extends Controller
{
    public function index(Request $request)
    {
        // --- Sistem Info ---
        $info = [
            'App Name'          => config('app.name'),
            'App Environment'   => App::environment(),
            'Debug Mode'        => config('app.debug') ? 'ON' : 'OFF',
            'Laravel Version'   => app()->version(),
            'PHP Version'       => phpversion(),
            'Database Driver'   => DB::getDriverName(),
            'Database Name'     => DB::connection()->getDatabaseName(),
            'Database Version'  => DB::selectOne('select version() as version')->version ?? 'Unknown',
            'Server Software'   => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'OS'                => PHP_OS,
            'Timezone'          => config('app.timezone'),
        ];

        // --- Log Error ---
        $logFile = storage_path('logs/laravel.log');
        $logs = [];

        if(File::exists($logFile)){
            $allLogs = file($logFile);

            if ($request->filled('level')) {
                $level = strtoupper($request->level);
                $logs = array_filter($allLogs, fn($line) => str_contains($line, $level));
            } else {
                $logs = $allLogs;
            }

            // ambil 50 baris terakhir
            $logs = array_slice($logs, -50);
            $logs = array_reverse($logs);
        }

        return view('admin.system.index', compact('info', 'logs'));
    }

    // Download log
    public function download()
    {
        $logFile = storage_path('logs/laravel.log');
        if(!File::exists($logFile)){
            abort(404, 'Log file not found.');
        }

        return response()->download($logFile, 'laravel.log');
    }
}
