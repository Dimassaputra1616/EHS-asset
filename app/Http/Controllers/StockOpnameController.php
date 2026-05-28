<?php

namespace App\Http\Controllers;

use App\Models\StockOpname;
use App\Models\Consumable;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ActivityLogger;

class StockOpnameController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = StockOpname::with(['consumable', 'user'])->select('stock_opnames.*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('consumable_name', function($row){
                    return $row->consumable ? $row->consumable->name : '-';
                })
                ->addColumn('consumable_code', function($row){
                    return $row->consumable ? $row->consumable->code : '-';
                })
                ->addColumn('auditor_name', function($row){
                    return $row->user ? $row->user->name : '-';
                })
                ->addColumn('status_badge', function($row){
                    if ($row->status === 'match') {
                        return '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i class="bi bi-check-circle me-1"></i> MATCH</span>';
                    }
                    $diffText = $row->difference > 0 ? '+' . $row->difference : $row->difference;
                    return '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1"><i class="bi bi-exclamation-triangle me-1"></i> SELISIH (' . $diffText . ')</span>';
                })
                ->addColumn('action', function($row){
                    $btn = '<button class="btn btn-action btn-view me-1" data-id="' . $row->id . '" title="View Details"><i class="bi bi-eye"></i></button>';
                    return $btn;
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        // Count stats for cards
        $totalAudits = StockOpname::count();
        $matchAudits = StockOpname::where('status', 'match')->count();
        $discrepancyAudits = StockOpname::where('status', 'discrepancy')->count();
        $netAdjustment = StockOpname::sum('difference');

        return view('stock_opnames.index', compact('totalAudits', 'matchAudits', 'discrepancyAudits', 'netAdjustment'));
    }

    public function create()
    {
        $consumables = Consumable::all();
        return view('stock_opnames.create', compact('consumables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'consumable_id' => 'required|exists:consumables,id',
            'physical_stock' => 'required|integer|min:0',
            'opname_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $consumable = Consumable::findOrFail($request->consumable_id);
        $systemStock = $consumable->stock;
        $physicalStock = $request->physical_stock;
        $difference = $physicalStock - $systemStock;
        $status = $difference === 0 ? 'match' : 'discrepancy';

        $opname = StockOpname::create([
            'consumable_id' => $request->consumable_id,
            'user_id' => Auth::id(),
            'system_stock' => $systemStock,
            'physical_stock' => $physicalStock,
            'difference' => $difference,
            'status' => $status,
            'notes' => $request->notes,
            'opname_date' => $request->opname_date,
        ]);

        // Auto-update consumable stock to match the audited physical stock!
        $consumable->stock = $physicalStock;
        $consumable->save();

        ActivityLogger::log('Stock Opname', "Stock Opname conducted for {$consumable->name} ({$consumable->code}). System stock: {$systemStock}, Physical stock: {$physicalStock}, Diff: {$difference}. Consumable stock updated.");

        return redirect()->route('stock-opnames.index')->with('success', 'Stock Opname recorded and stock updated successfully.');
    }

    public function show(StockOpname $stockOpname)
    {
        $stockOpname->load(['consumable', 'user']);
        return response()->json($stockOpname);
    }
}
