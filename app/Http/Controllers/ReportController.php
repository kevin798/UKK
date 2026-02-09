<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    // Petugas melihat & buat laporan
    public function petugasIndex()
    {
        $reports = Report::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('petugas.laporan', compact('reports'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lampiran' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('lampiran')) {
            $path = $request->file('lampiran')->store('laporan', 'public');
        }

        Report::create([
            'user_id' => Auth::id(),
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'lampiran' => $path,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Laporan berhasil dikirim ke admin.');
    }

    // Admin melihat semua laporan
    public function adminIndex()
    {
        $reports = Report::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.laporan', compact('reports'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:pending,resolved',
        ]);

        $report->update(['status' => $request->status]);

        return back()->with('success', 'Status laporan diperbarui.');
    }
}
