<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Consumable;
use App\Models\AssetRequest;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetRequestController extends Controller
{
    public function index()
    {
        $requests = AssetRequest::with(['asset', 'consumable'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('staff.requests.index', compact('requests'));
    }

    public function create()
    {
        $assets = Asset::whereNull('assigned_to')->orderBy('name', 'asc')->get();
        $consumables = Consumable::orderBy('name', 'asc')->get();

        return view('staff.requests.create', compact('assets', 'consumables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'request_type' => 'required|in:fixed_asset,consumable',
            'asset_id' => 'required_if:request_type,fixed_asset|nullable|exists:assets,id',
            'consumable_id' => 'required_if:request_type,consumable|nullable|exists:consumables,id',
            'qty' => 'required|integer|min:1',
            'purpose' => 'required|string|max:500',
        ]);

        $assetRequest = AssetRequest::create([
            'user_id' => Auth::id(),
            'request_type' => $request->request_type,
            'asset_id' => $request->request_type === 'fixed_asset' ? $request->asset_id : null,
            'consumable_id' => $request->request_type === 'consumable' ? $request->consumable_id : null,
            'qty' => $request->qty,
            'purpose' => $request->purpose,
            'status' => 'pending'
        ]);

        // Log this action
        $itemName = $assetRequest->request_type === 'fixed_asset' ? $assetRequest->asset->name : $assetRequest->consumable->name;
        ActivityLogger::log('Asset Request', "User requested to borrow/claim {$itemName} (Quantity: {$request->qty}).");

        return redirect()->route('staff.requests.index')->with('success', 'Pengajuan alat keselamatan berhasil dikirim!');
    }
}
