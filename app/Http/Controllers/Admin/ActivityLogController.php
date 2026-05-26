<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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
                ->rawColumns(['user_name', 'activity_badge'])
                ->make(true);
        }

        return view('admin.logs.index');
    }
}
