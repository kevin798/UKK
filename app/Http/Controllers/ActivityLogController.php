<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $logs = ActivityLog::with('user')
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);

        return view('user.log-aktivitas', compact('logs'));
    }

    public function indexAdmin(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->whereHas('user', fn($q) => $q->whereIn('role', ['admin', 'petugas']));

        if ($request->filled('start_date')) {
            $logs->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $logs->whereDate('created_at', '<=', $request->end_date);
        }

        $logs = $logs->latest()->paginate(10)->appends($request->only('start_date', 'end_date'));

        return view('admin.log-aktivitas', compact('logs'));
    }

    public function indexPetugas()
    {
        $logs = ActivityLog::with('user')
            ->whereHas('user', fn($q) => $q->where('role', 'petugas'))
            ->latest()
            ->paginate(10);

        return view('petugas.log-aktivitas', compact('logs'));
    }

    public function show($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);

        return response()->json([
            'id' => $log->id,
            'user' => $log->user ? ['id' => $log->user->id, 'name' => $log->user->name] : null,
            'activity' => $log->activity,
            'jumlah' => $log->jumlah,
            'tanggal_mulai' => $log->tanggal_mulai,
            'tanggal_selesai' => $log->tanggal_selesai,
            'description' => $log->description,
            'created_at' => $log->created_at->toDateTimeString(),
        ]);
    }
}
