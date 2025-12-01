<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        // Fetch logs with the associated user to avoid N+1 query issues
        $logs = AuditLog::with('user')
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);

        return view('audit_logs.index', [
            'logs' => $logs,
            'currentModule' => 'Audit Logs'
        ]);
    }
}