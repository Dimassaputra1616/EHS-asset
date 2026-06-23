<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetRequest;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;

class ManageRequestController extends Controller
{
    public function index()
    {
        $requests = AssetRequest::with(['user', 'asset', 'consumable'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.requests.index', compact('requests'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,returned',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        $assetRequest = AssetRequest::findOrFail($id);
        $oldStatus = $assetRequest->status;
        $newStatus = $request->status;

        $assetRequest->update([
            'status' => $newStatus,
            'admin_notes' => $request->admin_notes
        ]);

        // Automate Asset Assignment mapping if it's a fixed asset
        if ($assetRequest->request_type === 'fixed_asset' && $assetRequest->asset_id) {
            $asset = Asset::find($assetRequest->asset_id);
            if ($asset) {
                if ($newStatus === 'approved') {
                    $asset->update(['assigned_to' => $assetRequest->user->name]);
                } elseif ($newStatus === 'returned' || ($oldStatus === 'approved' && $newStatus === 'rejected')) {
                    if ($asset->assigned_to == $assetRequest->user->name || $asset->assigned_to == $assetRequest->user_id) {
                        $asset->update(['assigned_to' => null]);
                    }
                }
            }
        }

        $itemName = $assetRequest->request_type === 'fixed_asset' ? $assetRequest->asset->name : $assetRequest->consumable->name;
        ActivityLogger::log('Update Request Status', "Admin updated request #{$id} ({$itemName}) status from '{$oldStatus}' to '{$newStatus}'.");

        return response()->json([
            'success' => true,
            'message' => 'Status pengajuan berhasil diperbarui!'
        ]);
    }

    public function destroy($id)
    {
        $assetRequest = AssetRequest::findOrFail($id);
        
        $itemName = $assetRequest->request_type === 'fixed_asset' ? ($assetRequest->asset ? $assetRequest->asset->name : 'Unknown') : ($assetRequest->consumable ? $assetRequest->consumable->name : 'Unknown');
        ActivityLogger::log('Delete Request', "Admin deleted request #{$id} ({$itemName}) submitted by {$assetRequest->user->name}.");
        
        $assetRequest->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan berhasil dihapus!'
        ]);
    }
}
