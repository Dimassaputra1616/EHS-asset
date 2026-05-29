<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\ActivityLogger;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ActivityLog::with('user')->select('activity_logs.*')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user_name', function ($row) {
                    return $row->user ? $row->user->name : '<span class="text-muted fst-italic">System</span>';
                })
                ->addColumn('formatted_date', function ($row) {
                    return $row->created_at->format('d M Y, H:i:s');
                })
                ->addColumn('activity_badge', function ($row) {
                    $badgeClass = 'bg-info';
                    $activity = $row->activity;
                    
                    if (str_contains($activity, 'Create')) {
                        $badgeClass = 'bg-success';
                    } elseif (str_contains($activity, 'Update')) {
                        $badgeClass = 'bg-warning text-dark';
                    } elseif (str_contains($activity, 'Delete')) {
                        $badgeClass = 'bg-danger';
                    } elseif ($activity === 'Login') {
                        $badgeClass = 'bg-primary';
                    } elseif ($activity === 'Logout') {
                        $badgeClass = 'bg-secondary';
                    }
                    
                    return '<span class="badge ' . $badgeClass . '">' . htmlspecialchars($activity) . '</span>';
                })
                ->addColumn('action', function($row){
                    return '<button class="btn btn-sm btn-outline-danger border-0 btn-delete-log" data-id="' . $row->id . '" title="Hapus Log"><i class="bi bi-trash"></i></button>';
                })
                ->rawColumns(['user_name', 'activity_badge', 'action'])
                ->make(true);
        }

        return view('admin.logs.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $log = ActivityLog::findOrFail($id);
        $log->delete();

        return response()->json([
            'success' => true,
            'message' => 'Log aktivitas berhasil dihapus.'
        ]);
    }

    /**
     * Clear all logs from storage.
     */
    public function clearAll(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $start = \Carbon\Carbon::parse($startDate)->startOfDay();
            $end = \Carbon\Carbon::parse($endDate)->endOfDay();

            $count = ActivityLog::whereBetween('created_at', [$start, $end])->count();
            
            if ($count === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ditemukan log aktivitas pada rentang tanggal terpilih.'
                ]);
            }

            ActivityLog::whereBetween('created_at', [$start, $end])->delete();

            // Log this deletion range activity
            ActivityLogger::log('Delete Logs', "Administrator cleared {$count} system activity logs from {$startDate} to {$endDate}.");

            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$count} log aktivitas dari tanggal {$startDate} hingga {$endDate}."
            ]);
        } else {
            $count = ActivityLog::count();
            ActivityLog::truncate();

            // Log this action
            ActivityLogger::log('Delete Logs', 'Administrator cleared all system activity logs.');

            return response()->json([
                'success' => true,
                'message' => 'Semua log aktivitas sistem berhasil dikosongkan.'
            ]);
        }
    }
}
