<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Consumable;
use App\Models\DamageReport;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DamageReportController extends Controller
{
    public function index()
    {
        $reports = DamageReport::with(['asset', 'consumable'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('staff.damage_reports.index', compact('reports'));
    }

    public function create()
    {
        $assets = Asset::orderBy('name', 'asc')->get();
        $consumables = Consumable::orderBy('name', 'asc')->get();

        return view('staff.damage_reports.create', compact('assets', 'consumables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_type' => 'required|in:fixed_asset,consumable,other',
            'asset_id' => 'required_if:item_type,fixed_asset|nullable|exists:assets,id',
            'consumable_id' => 'required_if:item_type,consumable|nullable|exists:consumables,id',
            'item_name' => 'required_if:item_type,other|nullable|string|max:255',
            'description' => 'required|string|max:1000',
            'urgency' => 'required|in:low,medium,high',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('damage_reports', 'public');
        }

        $itemName = $request->item_name;
        if ($request->item_type === 'fixed_asset') {
            $itemName = Asset::find($request->asset_id)->name;
        } elseif ($request->item_type === 'consumable') {
            $itemName = Consumable::find($request->consumable_id)->name;
        }

        DamageReport::create([
            'user_id' => Auth::id(),
            'asset_id' => $request->item_type === 'fixed_asset' ? $request->asset_id : null,
            'consumable_id' => $request->item_type === 'consumable' ? $request->consumable_id : null,
            'item_name' => $itemName,
            'description' => $request->description,
            'photo' => $photoPath,
            'urgency' => $request->urgency,
            'status' => 'pending'
        ]);

        ActivityLogger::log('Damage Report', "User submitted a damage report for '{$itemName}' with urgency '{$request->urgency}'.");

        return redirect()->route('staff.damage_reports.index')->with('success', 'Laporan kerusakan alat berhasil dikirim!');
    }
}
