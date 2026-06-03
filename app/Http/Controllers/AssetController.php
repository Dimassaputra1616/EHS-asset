<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Asset::with(['category', 'location', 'supplier'])->select('assets.*');
            if ($request->filled('category_id')) {
                $data->where('category_id', $request->category_id);
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('category_name', function($row){
                    return $row->category ? $row->category->name : '-';
                })
                ->addColumn('location_name', function($row){
                    return $row->location ? $row->location->name : '-';
                })
                ->addColumn('action', function($row){
                    $btn = '<button class="btn btn-action btn-view me-1" data-id="' . $row->id . '" title="View"><i class="bi bi-eye"></i></button>';
                    $btn .= '<a href="' . route('assets.edit', $row->id) . '" class="btn btn-action btn-edit me-1" title="Edit"><i class="bi bi-pencil"></i></a>';
                    $btn .= '<button class="btn btn-action btn-delete" data-id="' . $row->id . '" data-name="' . htmlspecialchars($row->name) . '" title="Delete"><i class="bi bi-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('assets.index');
    }

    public function create()
    {
        $categories = \App\Models\Category::where('type', 'asset')->get();
        $locations = \App\Models\Location::all();
        $suppliers = \App\Models\Supplier::all();
        return view('assets.create', compact('categories', 'locations', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:assets,code',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'condition' => 'required|string',
            'status' => 'required|string',
            'purchase_date' => 'nullable|date',
            'purchase_cost' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|string|max:255',
        ]);

        $asset = Asset::create($request->all());

        \App\Helpers\ActivityLogger::log('Create Asset', "Asset {$asset->name} ({$asset->code}) was created.");

        return redirect()->route('assets.index')->with('success', 'Asset created successfully.');
    }

    public function show(Asset $asset)
    {
        $asset->load(['category', 'location', 'supplier']);
        return response()->json($asset);
    }

    public function edit(Asset $asset)
    {
        $categories = \App\Models\Category::where('type', 'asset')->get();
        $locations = \App\Models\Location::all();
        $suppliers = \App\Models\Supplier::all();
        return view('assets.edit', compact('asset', 'categories', 'locations', 'suppliers'));
    }

    public function update(Request $request, Asset $asset)
    {
        if ($request->has('quick_update')) {
            $request->validate([
                'status' => 'required|string',
                'assigned_to' => 'nullable|string|max:255',
            ]);
            $asset->update($request->only(['status', 'assigned_to']));
            \App\Helpers\ActivityLogger::log('Quick Update Asset', "Asset {$asset->name} ({$asset->code}) status updated to {$asset->status} (Holder: " . ($asset->assigned_to ?? '-') . ").");
            
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Status aset berhasil diperbarui!']);
            }
            return redirect()->back()->with('success', 'Status aset berhasil diperbarui!');
        }

        $request->validate([
            'code' => 'required|string|max:255|unique:assets,code,'.$asset->id,
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'condition' => 'required|string',
            'status' => 'required|string',
            'purchase_date' => 'nullable|date',
            'purchase_cost' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|string|max:255',
        ]);

        $asset->update($request->all());

        \App\Helpers\ActivityLogger::log('Update Asset', "Asset {$asset->name} ({$asset->code}) was updated.");

        return redirect()->route('assets.index')->with('success', 'Asset updated successfully.');
    }

    public function destroy(Asset $asset)
    {
        \App\Helpers\ActivityLogger::log('Delete Asset', "Asset {$asset->name} ({$asset->code}) was deleted.");
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully.');
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
        if (str_contains(strtolower($name), 'apar')) $prefix = 'APR';
        if (str_contains(strtolower($name), 'handy') || str_contains(strtolower($name), 'ht')) $prefix = 'HT';
        if (str_contains(strtolower($name), 'hydrant')) $prefix = 'HYD';

        // Find next sequence
        $codePrefix = config('app.asset_code_prefix', 'AST');
        $lastAsset = Asset::where('code', 'like', "{$codePrefix}-{$prefix}-%")
            ->orderBy('code', 'desc')
            ->first();

        $nextSeq = 1;
        if ($lastAsset) {
            $parts = explode('-', $lastAsset->code);
            $lastSeq = end($parts);
            if (is_numeric($lastSeq)) {
                $nextSeq = intval($lastSeq) + 1;
            }
        }
        
        $code = sprintf("%s-%s-%03d", $codePrefix, $prefix, $nextSeq);
        return response()->json(['code' => $code]);
    }

    public function export(Request $request)
    {
        $query = Asset::with(['category', 'location', 'supplier']);

        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        $assets = $query->get();

        $filename = 'assets_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() use ($assets) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel detection
            fputs($file, "\xEF\xBB\xBF");

            // Header row
            fputcsv($file, [
                'No', 
                'Asset Code', 
                'Asset Name', 
                'Category', 
                'Location', 
                'Supplier / Vendor', 
                'Condition', 
                'Status', 
                'Holder / Assigned To',
                'Purchase Date', 
                'Purchase Cost (Rp)', 
                'Description / Notes'
            ]);

            foreach ($assets as $index => $asset) {
                fputcsv($file, [
                    $index + 1,
                    $asset->code,
                    $asset->name,
                    $asset->category ? $asset->category->name : '-',
                    $asset->location ? $asset->location->name : '-',
                    $asset->supplier ? $asset->supplier->name : '-',
                    $asset->condition,
                    $asset->status,
                    $asset->assigned_to ? $asset->assigned_to : '-',
                    $asset->purchase_date ? $asset->purchase_date : '-',
                    ceil($asset->purchase_cost),
                    $asset->description ? $asset->description : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function getNotifications()
    {
        $user = auth()->user();
        $notifications = [];

        if ($user && ($user->can('requests.manage') || $user->can('damage_reports.manage') || $user->can('config.manage') || $user->hasRole('admin'))) {
            // --- ADMIN & STAFF NOTIFICATIONS ---

            // 1. Pending Requests (Permintaan Pinjam Baru)
            $pendingRequests = \App\Models\AssetRequest::with(['user', 'asset', 'consumable'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();
            
            foreach ($pendingRequests as $req) {
                $itemName = $req->asset ? $req->asset->name : ($req->consumable ? $req->consumable->name : 'Unknown Item');
                $notifications[] = [
                    'id' => 'request-' . $req->id,
                    'title' => 'Permintaan Pinjam Baru',
                    'message' => "{$req->user->name} mengajukan pinjam {$itemName} (Qty: {$req->qty})",
                    'type' => 'warning',
                    'url' => route('admin.requests.index'),
                    'time' => $req->created_at->diffForHumans()
                ];
            }

            // 2. Pending Damage Reports (Laporan Kerusakan Baru)
            $pendingDamage = \App\Models\DamageReport::with(['user', 'asset', 'consumable'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($pendingDamage as $dmg) {
                $itemName = $dmg->asset ? $dmg->asset->name : ($dmg->consumable ? $dmg->consumable->name : $dmg->item_name);
                $notifications[] = [
                    'id' => 'damage-' . $dmg->id,
                    'title' => 'Laporan Kerusakan Baru',
                    'message' => "{$dmg->user->name} melaporkan kerusakan: {$itemName}",
                    'type' => 'danger',
                    'url' => route('admin.damage_reports.index'),
                    'time' => $dmg->created_at->diffForHumans()
                ];
            }

            // 3. Low Stock Consumables
            $threshold = (int) config('app.low_stock_threshold', 10);
            $lowStockConsumables = \App\Models\Consumable::where('stock', '<=', $threshold)->get();
            foreach ($lowStockConsumables as $item) {
                $notifications[] = [
                    'id' => 'consumable-' . $item->id,
                    'title' => 'Stok Menipis (Warning)',
                    'message' => "Stok {$item->name} sisa {$item->stock} {$item->unit} (Batas Min: {$threshold})",
                    'type' => 'warning',
                    'url' => route('consumables.index') . '?low_stock=1',
                    'time' => 'Tindakan diperlukan'
                ];
            }

            // 4. Critical Assets that are "Broken"
            $brokenAssets = \App\Models\Asset::where('condition', 'Broken')->get();
            foreach ($brokenAssets as $asset) {
                $notifications[] = [
                    'id' => 'asset-' . $asset->id,
                    'title' => 'Aset Rusak',
                    'message' => "Aset '{$asset->name}' ({$asset->code}) dilaporkan rusak.",
                    'type' => 'danger',
                    'url' => route('assets.index') . "?search=" . urlencode($asset->code),
                    'time' => 'Perlu ditinjau'
                ];
            }
        } else if ($user) {
            // --- EMPLOYEE / KARYAWAN (STAFF PORTAL) NOTIFICATIONS ---

            // 1. Approved / Rejected / Returned Request Updates
            $userRequests = \App\Models\AssetRequest::with(['asset', 'consumable'])
                ->where('user_id', $user->id)
                ->whereIn('status', ['approved', 'rejected', 'returned'])
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();
            
            foreach ($userRequests as $req) {
                $itemName = $req->asset ? $req->asset->name : ($req->consumable ? $req->consumable->name : 'Item');
                $statusMap = [
                    'approved' => ['Disetujui', 'success', 'telah disetujui oleh admin.'],
                    'rejected' => ['Ditolak', 'danger', 'ditolak.'],
                    'returned' => ['Dikembalikan', 'info', 'telah dikembalikan dan dikonfirmasi.'],
                ];
                $statusInfo = $statusMap[$req->status] ?? ['Selesai', 'info', 'diperbarui.'];
                
                $notifications[] = [
                    'id' => 'user-request-' . $req->id . '-' . $req->status,
                    'title' => 'Peminjaman ' . $statusInfo[0],
                    'message' => "Permintaan pinjam {$itemName} Anda {$statusInfo[2]}",
                    'type' => $statusInfo[1],
                    'url' => route('staff.requests.index'),
                    'time' => $req->updated_at->diffForHumans()
                ];
            }

            // 2. Resolved / Closed Damage Reports
            $userDamage = \App\Models\DamageReport::with(['asset', 'consumable'])
                ->where('user_id', $user->id)
                ->whereIn('status', ['resolved', 'closed'])
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();

            foreach ($userDamage as $dmg) {
                $itemName = $dmg->asset ? $dmg->asset->name : ($dmg->consumable ? $dmg->consumable->name : $dmg->item_name);
                $statusMap = [
                    'resolved' => ['Selesai Diperbaiki', 'success', 'telah selesai diperbaiki.'],
                    'closed' => ['Laporan Ditutup', 'secondary', 'telah ditutup.'],
                ];
                $statusInfo = $statusMap[$dmg->status] ?? ['Diperbarui', 'info', 'diperbarui.'];

                $notifications[] = [
                    'id' => 'user-damage-' . $dmg->id . '-' . $dmg->status,
                    'title' => $statusInfo[0],
                    'message' => "Laporan kerusakan {$itemName} Anda {$statusInfo[2]}",
                    'type' => $statusInfo[1],
                    'url' => route('staff.damage_reports.index'),
                    'time' => $dmg->updated_at->diffForHumans()
                ];
            }
        }

        return response()->json([
            'count' => count($notifications),
            'notifications' => $notifications
        ]);
    }

    public function globalSearch(Request $request)
    {
        $q = $request->query('q');
        if (empty($q) || strlen($q) < 2) {
            return response()->json([
                'results' => []
            ]);
        }

        $results = [];

        // Search Fixed Assets
        $assets = \App\Models\Asset::with('category')
            ->where('name', 'like', "%{$q}%")
            ->orWhere('code', 'like', "%{$q}%")
            ->take(5)
            ->get();

        foreach ($assets as $asset) {
            $results[] = [
                'title' => $asset->name,
                'subtitle' => $asset->code . ' • ' . ($asset->category ? $asset->category->name : 'Asset'),
                'url' => route('assets.index') . "?search=" . urlencode($asset->code),
                'type' => 'Asset',
                'icon' => 'bi-box-seam'
            ];
        }

        // Search Consumables
        $consumables = \App\Models\Consumable::with('category')
            ->where('name', 'like', "%{$q}%")
            ->take(5)
            ->get();

        foreach ($consumables as $item) {
            $results[] = [
                'title' => $item->name,
                'subtitle' => "Stock: {$item->stock} {$item->unit} • " . ($item->category ? $item->category->name : 'Consumable'),
                'url' => route('consumables.index') . "?search=" . urlencode($item->name),
                'type' => 'Consumable',
                'icon' => 'bi-basket'
            ];
        }

        // Search Categories
        $categories = \App\Models\Category::where('name', 'like', "%{$q}%")
            ->take(3)
            ->get();

        foreach ($categories as $cat) {
            $results[] = [
                'title' => $cat->name,
                'subtitle' => "Category Profile",
                'url' => route('categories.index') . "?search=" . urlencode($cat->name),
                'type' => 'Category',
                'icon' => 'bi-tags'
            ];
        }

        return response()->json([
            'results' => $results
        ]);
    }

    public function getByCode($code)
    {
        // Try to find in Fixed Assets
        $asset = \App\Models\Asset::with(['category', 'location', 'supplier'])->where('code', $code)->first();
        if ($asset) {
            return response()->json([
                'found' => true,
                'type' => 'fixed_asset',
                'data' => $asset
            ]);
        }

        // Try to find in Consumables
        $consumable = \App\Models\Consumable::with(['category'])->where('code', $code)->first();
        if ($consumable) {
            return response()->json([
                'found' => true,
                'type' => 'consumable',
                'data' => $consumable
            ]);
        }

        return response()->json([
            'found' => false,
            'message' => 'Alat keselamatan atau APD tidak ditemukan dengan barcode: ' . $code
        ], 404);
    }
}
