<?php

namespace App\Http\Controllers;

use App\Models\Consumable;
use App\Models\ConsumableTransaction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ConsumableTransactionController extends Controller
{
    /**
     * Display a listing of incoming transactions (Stock In).
     */
    public function indexIn(Request $request)
    {
        if ($request->ajax()) {
            $data = ConsumableTransaction::with(['consumable', 'user'])
                ->where('type', 'in')
                ->select('consumable_transactions.*')
                ->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('consumable_name', function ($row) {
                    return $row->consumable ? $row->consumable->name : '-';
                })
                ->addColumn('user_name', function ($row) {
                    return $row->user ? $row->user->name : '<span class="text-muted fst-italic">System</span>';
                })
                ->addColumn('formatted_date', function ($row) {
                    return $row->date->format('d M Y');
                })
                ->addColumn('formatted_quantity', function ($row) {
                    $unit = $row->consumable ? $row->consumable->unit : 'pcs';
                    return '<span class="text-success fw-bold"><i class="bi bi-plus-lg me-1"></i>' . $row->quantity . ' ' . $unit . '</span>';
                })
                ->addColumn('action', function ($row) {
                    if (auth()->user()->can('consumables.delete')) {
                        return '<button class="btn btn-action btn-delete" data-id="' . $row->id . '" title="Delete"><i class="bi bi-trash"></i></button>';
                    }
                    return '-';
                })
                ->rawColumns(['user_name', 'formatted_quantity', 'action'])
                ->make(true);
        }

        return view('consumables.transactions.in');
    }

    /**
     * Display a listing of outgoing transactions (Stock Out).
     */
    public function indexOut(Request $request)
    {
        if ($request->ajax()) {
            $data = ConsumableTransaction::with(['consumable', 'user'])
                ->where('type', 'out')
                ->select('consumable_transactions.*')
                ->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('consumable_name', function ($row) {
                    return $row->consumable ? $row->consumable->name : '-';
                })
                ->addColumn('user_name', function ($row) {
                    return $row->user ? $row->user->name : '<span class="text-muted fst-italic">System</span>';
                })
                ->addColumn('formatted_date', function ($row) {
                    return $row->date->format('d M Y');
                })
                ->addColumn('formatted_quantity', function ($row) {
                    $unit = $row->consumable ? $row->consumable->unit : 'pcs';
                    return '<span class="text-danger fw-bold"><i class="bi bi-dash-lg me-1"></i>' . $row->quantity . ' ' . $unit . '</span>';
                })
                ->addColumn('action', function ($row) {
                    if (auth()->user()->can('consumables.delete')) {
                        return '<button class="btn btn-action btn-delete" data-id="' . $row->id . '" title="Delete"><i class="bi bi-trash"></i></button>';
                    }
                    return '-';
                })
                ->rawColumns(['user_name', 'formatted_quantity', 'action'])
                ->make(true);
        }

        return view('consumables.transactions.out');
    }

    /**
     * Show the form for creating a new transaction.
     */
    public function create(Request $request)
    {
        $type = $request->query('type', 'in');
        if (!in_array($type, ['in', 'out'])) {
            $type = 'in';
        }

        $consumables = Consumable::all();
        return view('consumables.transactions.create', compact('type', 'consumables'));
    }

    /**
     * Store a newly created transaction in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'consumable_id' => 'required|exists:consumables,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $consumable = Consumable::findOrFail($request->consumable_id);

        if ($request->type === 'out' && $consumable->stock < $request->quantity) {
            return back()->withErrors([
                'quantity' => 'Stock tidak mencukupi. Sisa stock saat ini: ' . $consumable->stock . ' ' . $consumable->unit
            ])->withInput();
        }

        // Create transaction
        $transaction = ConsumableTransaction::create([
            'consumable_id' => $request->consumable_id,
            'user_id' => auth()->id(),
            'type' => $request->type,
            'quantity' => $request->quantity,
            'date' => $request->date,
            'notes' => $request->notes,
        ]);

        // Adjust stock
        if ($request->type === 'in') {
            $consumable->increment('stock', $request->quantity);
            \App\Helpers\ActivityLogger::log('Stock In Consumable', "Stock In: Added {$request->quantity} {$consumable->unit} of {$consumable->name}.");
        } else {
            $consumable->decrement('stock', $request->quantity);
            \App\Helpers\ActivityLogger::log('Stock Out Consumable', "Stock Out: Deducted {$request->quantity} {$consumable->unit} of {$consumable->name}.");
        }

        $routeName = $request->type === 'in' ? 'consumables.transactions.in' : 'consumables.transactions.out';
        return redirect()->route($routeName)->with('success', 'Transaksi berhasil disimpan.');
    }

    /**
     * Remove the specified transaction and reverse its stock changes.
     */
    public function destroy($id)
    {
        $transaction = ConsumableTransaction::findOrFail($id);
        $consumable = Consumable::findOrFail($transaction->consumable_id);

        if ($transaction->type === 'in') {
            // Reversing "Stock In" means we subtract the stock back
            // Check if there is enough stock to subtract
            if ($consumable->stock < $transaction->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus transaksi. Sisa stok saat ini (' . $consumable->stock . ') tidak mencukupi jika dikurangi sebanyak ' . $transaction->quantity
                ], 422);
            }
            $consumable->decrement('stock', $transaction->quantity);
            \App\Helpers\ActivityLogger::log('Delete Stock In', "Deleted Stock In Transaction #{$transaction->id}: Deducted {$transaction->quantity} {$consumable->unit} of {$consumable->name}.");
        } else {
            // Reversing "Stock Out" means we add the stock back
            $consumable->increment('stock', $transaction->quantity);
            \App\Helpers\ActivityLogger::log('Delete Stock Out', "Deleted Stock Out Transaction #{$transaction->id}: Added back {$transaction->quantity} {$consumable->unit} of {$consumable->name}.");
        }

        $transaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dihapus dan stok telah disesuaikan kembali!'
        ]);
    }
}
