<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AuditTrailController extends Controller
{
    /**
     * Display audit trail.
     */
    public function index()
    {
        $logPath = storage_path('logs/audit.log');
        $entries = [];

        if (File::exists($logPath)) {
            $lines = File::lines($logPath);
            foreach ($lines as $line) {
                $data = json_decode($line, true);
                if ($data && isset($data['action'])) {
                    $entries[] = $data;
                }
            }
            // Reverse to show newest first
            $entries = array_reverse($entries);
        }

        return view('admin.audit_trail', compact('entries'));
    }

    /**
     * Log an admin action.
     */
    public static function log(string $action, string $target, string $details = ''): void
    {
        $entry = [
            'timestamp' => now()->toDateTimeString(),
            'user_id' => Auth::id() ?? 'system',
            'user_name' => Auth::user() ? Auth::user()->first_name . ' ' . Auth::user()->last_name : 'System',
            'user_role' => Auth::user() ? Auth::user()->role : 'system',
            'action' => $action,
            'target' => $target,
            'details' => $details,
            'ip_address' => request()->ip(),
        ];

        $logPath = storage_path('logs/audit.log');
        File::append($logPath, json_encode($entry) . PHP_EOL);
    }
}
