<?php

namespace App\Http\Controllers;

use App\Models\Consumable;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ConsumableController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Consumable::with(['category', 'supplier'])->select('consumables.*');
            
            if ($request->filled('category_id')) {
                $data->where('category_id', $request->category_id);
            }

            if ($request->query('low_stock') == '1') {
                $data->whereColumn('stock', '<=', 'min_stock');
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('category_name', function($row){
                    return $row->category ? $row->category->name : '-';
                })
                ->addColumn('stock_status', function($row){
                    if ($row->stock <= $row->min_stock) {
                        return '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1"><i class="bi bi-exclamation-triangle me-1"></i> LOW STOCK</span>';
                    }
                    return '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i class="bi bi-check-circle me-1"></i> IN STOCK</span>';
                })
                ->addColumn('action', function($row){
                    $btn = '<button class="btn btn-action btn-view me-1" data-id="' . $row->id . '" title="View"><i class="bi bi-eye"></i></button>';
                    $btn .= '<a href="' . route('consumables.edit', $row->id) . '" class="btn btn-action btn-edit me-1" title="Edit"><i class="bi bi-pencil"></i></a>';
                    $btn .= '<button class="btn btn-action btn-delete" data-id="' . $row->id . '" data-name="' . htmlspecialchars($row->name) . '" title="Delete"><i class="bi bi-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['stock_status', 'action'])
                ->make(true);
        }

        return view('consumables.index');
    }

    public function create()
    {
        $categories = \App\Models\Category::where('type', 'consumable')->get();
        $suppliers = \App\Models\Supplier::all();
        return view('consumables.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:consumables,code',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'unit' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $consumable = Consumable::create($request->all());

        \App\Helpers\ActivityLogger::log('Create Consumable', "Consumable {$consumable->name} ({$consumable->code}) was created with stock: {$consumable->stock} {$consumable->unit}.");

        return redirect()->route('consumables.index')->with('success', 'Consumable created successfully.');
    }

    public function show(Consumable $consumable)
    {
        $consumable->load(['category', 'supplier']);
        return response()->json($consumable);
    }

    public function edit(Consumable $consumable)
    {
        $categories = \App\Models\Category::where('type', 'consumable')->get();
        $suppliers = \App\Models\Supplier::all();
        return view('consumables.edit', compact('consumable', 'categories', 'suppliers'));
    }

    public function update(Request $request, Consumable $consumable)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:consumables,code,'.$consumable->id,
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'unit' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $consumable->update($request->all());

        \App\Helpers\ActivityLogger::log('Update Consumable', "Consumable {$consumable->name} ({$consumable->code}) was updated. New stock: {$consumable->stock} {$consumable->unit}.");

        return redirect()->route('consumables.index')->with('success', 'Consumable updated successfully.');
    }

    public function destroy(Consumable $consumable)
    {
        \App\Helpers\ActivityLogger::log('Delete Consumable', "Consumable {$consumable->name} was deleted.");
        $consumable->delete();
        return redirect()->route('consumables.index')->with('success', 'Consumable deleted successfully.');
    }

    public function export(Request $request)
    {
        $query = Consumable::with(['category', 'supplier']);

        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        $consumables = $query->get();

        $filename = 'consumables_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() use ($consumables) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fputs($file, "\xEF\xBB\xBF");

            // Header row
            fputcsv($file, [
                'No', 
                'Consumable Code',
                'Consumable Name', 
                'Category', 
                'Supplier / Vendor', 
                'Current Stock', 
                'Min. Stock Requirement', 
                'Unit', 
                'Description / Notes'
            ]);

            foreach ($consumables as $index => $consumable) {
                fputcsv($file, [
                    $index + 1,
                    $consumable->code,
                    $consumable->name,
                    $consumable->category ? $consumable->category->name : '-',
                    $consumable->supplier ? $consumable->supplier->name : '-',
                    $consumable->stock,
                    $consumable->min_stock,
                    $consumable->unit,
                    $consumable->description ? $consumable->description : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function generateCode(Request $request, $categoryId)
    {
        $category = \App\Models\Category::findOrFail($categoryId);
        $name = $category->name;
        
        // Clean prefix extraction
        preg_match('/^([^\(]+)/', $name, $matches);
        $cleanName = trim($matches[1] ?? $name);
        
        $words = explode(' ', $cleanName);
        if (count($words) >= 2) {
            $prefix = '';
            foreach ($words as $w) {
                if (!empty($w)) {
                    $prefix .= substr($w, 0, 1);
                }
            }
        } else {
            $prefix = substr($cleanName, 0, 3);
        }
        $prefix = strtoupper(trim($prefix));
        
        // Specific overrides for accuracy
        if (str_contains(strtolower($name), 'helm')) $prefix = 'HLM';
        if (str_contains(strtolower($name), 'p3k') || str_contains(strtolower($name), 'first aid')) $prefix = 'P3K';
        if (str_contains(strtolower($name), 'atk')) $prefix = 'ATK';
        if (str_contains(strtolower($name), 'safety')) $prefix = 'SFT';
        if (str_contains(strtolower($name), 'sepatu')) $prefix = 'SPT';

        // Find next sequence
        $lastConsumable = Consumable::where('code', 'like', "HSE-{$prefix}-%")
            ->orderBy('code', 'desc')
            ->first();

        $nextSeq = 1;
        if ($lastConsumable) {
            $parts = explode('-', $lastConsumable->code);
            $lastSeq = end($parts);
            if (is_numeric($lastSeq)) {
                $nextSeq = intval($lastSeq) + 1;
            }
        }
        
        $code = sprintf("HSE-%s-%03d", $prefix, $nextSeq);
        return response()->json(['code' => $code]);
    }
}
