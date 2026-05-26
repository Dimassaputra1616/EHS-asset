<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppConfig;
use Illuminate\Http\Request;

class AppConfigController extends Controller
{
    public function index()
    {
        $configs = AppConfig::all()->groupBy('group');
        return view('admin.config.index', compact('configs'));
    }

    public function update(Request $request)
    {
        // 1. Intercept standard input fields except framework security tokens
        $data = $request->except(['_token', '_method']);

        // 2. Process custom logo file uploads securely
        if ($request->hasFile('app_logo')) {
            $request->validate([
                'app_logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);

            // Retrieve and purge the old logo file to prevent storage bloat
            $oldLogo = AppConfig::where('key', 'app_logo')->value('value');
            if ($oldLogo && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldLogo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldLogo);
            }

            // Store the newly uploaded logo inside public storage disk
            $path = $request->file('app_logo')->store('logos', 'public');

            // Record relative file path in database config entry
            AppConfig::where('key', 'app_logo')->update(['value' => $path]);

            // Unset key from data payload so we don't try to double update it
            unset($data['app_logo']);
        }

        // 3. Process and update remaining configuration parameters
        foreach ($data as $key => $value) {
            AppConfig::where('key', $key)->update(['value' => $value]);
        }

        return redirect()->back()->with('success', 'Configuration updated successfully.');
    }
}
