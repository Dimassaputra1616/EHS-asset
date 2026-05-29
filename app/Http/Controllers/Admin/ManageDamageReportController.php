<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DamageReport;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;

class ManageDamageReportController extends Controller
{
    public function index()
    {
        $reports = DamageReport::with(['user', 'asset', 'consumable'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.damage_reports.index', compact('reports'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,investigating,resolved,closed',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        $report = DamageReport::findOrFail($id);
        $oldStatus = $report->status;
        $newStatus = $request->status;

        $report->update([
            'status' => $newStatus,
            'admin_notes' => $request->admin_notes
        ]);

        ActivityLogger::log('Update Damage Report Status', "Admin updated damage report #{$id} ({$report->item_name}) status from '{$oldStatus}' to '{$newStatus}'.");

        return response()->json([
            'success' => true,
            'message' => 'Status laporan kerusakan berhasil diperbarui!'
        ]);
    }
}
