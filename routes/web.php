<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\ConsumableController;
use App\Http\Controllers\ConsumableTransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\Admin\AppConfigController;
use App\Http\Controllers\StockOpnameController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Assets
    Route::get('assets/export', [AssetController::class, 'export'])->name('assets.export');
    Route::get('assets/generate-code/{category_id}', [AssetController::class, 'generateCode'])->name('assets.generate-code');
    Route::resource('assets', AssetController::class);

    // API Services
    Route::get('api/notifications', [AssetController::class, 'getNotifications'])->name('api.notifications');
    Route::get('api/search', [AssetController::class, 'globalSearch'])->name('api.search');

    // Consumable Transactions (must be registered BEFORE the resource route)
    Route::get('consumables/transactions/in', [ConsumableTransactionController::class, 'indexIn'])->name('consumables.transactions.in');
    Route::get('consumables/transactions/out', [ConsumableTransactionController::class, 'indexOut'])->name('consumables.transactions.out');
    Route::get('consumables/transactions/create', [ConsumableTransactionController::class, 'create'])->name('consumables.transactions.create');
    Route::post('consumables/transactions', [ConsumableTransactionController::class, 'store'])->name('consumables.transactions.store');

    // Consumables
    Route::get('consumables/export', [ConsumableController::class, 'export'])->name('consumables.export');
    Route::get('consumables/generate-code/{category_id}', [ConsumableController::class, 'generateCode'])->name('consumables.generate-code');
    Route::resource('consumables', ConsumableController::class);

    // Stock Opnames
    Route::resource('stock-opnames', StockOpnameController::class);

    // Master Data
    Route::get('categories/{category}/items', [CategoryController::class, 'items'])->name('categories.items');
    Route::resource('categories', CategoryController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('suppliers', SupplierController::class);

    // Staff Request & Reporting Portals (Any authenticated employee/staff)
    Route::prefix('staff')->name('staff.')->group(function () {
        // Asset Requests
        Route::get('/requests', [\App\Http\Controllers\Staff\AssetRequestController::class, 'index'])->name('requests.index');
        Route::get('/requests/create', [\App\Http\Controllers\Staff\AssetRequestController::class, 'create'])->name('requests.create');
        Route::post('/requests', [\App\Http\Controllers\Staff\AssetRequestController::class, 'store'])->name('requests.store');

        // Damage/Incident Reports
        Route::get('/damage-reports', [\App\Http\Controllers\Staff\DamageReportController::class, 'index'])->name('damage_reports.index');
        Route::get('/damage-reports/create', [\App\Http\Controllers\Staff\DamageReportController::class, 'create'])->name('damage_reports.create');
        Route::post('/damage-reports', [\App\Http\Controllers\Staff\DamageReportController::class, 'store'])->name('damage_reports.store');
    });

    // Administration (Admin only)
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/configs', [AppConfigController::class, 'index'])->name('configs.index');
        Route::put('/configs', [AppConfigController::class, 'update'])->name('configs.update');
        
        // Users & Roles
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::put('users/{user}/role', [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.update-role');
        Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);

        // Activity Logs
        Route::get('/logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('logs.index');
        Route::delete('/logs/clear', [\App\Http\Controllers\Admin\ActivityLogController::class, 'clearAll'])->name('logs.clear');
        Route::delete('/logs/{id}', [\App\Http\Controllers\Admin\ActivityLogController::class, 'destroy'])->name('logs.destroy');

        // Manage Asset Requests
        Route::get('/requests', [\App\Http\Controllers\Admin\ManageRequestController::class, 'index'])->name('requests.index');
        Route::put('/requests/{id}/status', [\App\Http\Controllers\Admin\ManageRequestController::class, 'updateStatus'])->name('requests.update-status');

        // Manage Damage Reports
        Route::get('/damage-reports', [\App\Http\Controllers\Admin\ManageDamageReportController::class, 'index'])->name('damage_reports.index');
        Route::put('/damage-reports/{id}/status', [\App\Http\Controllers\Admin\ManageDamageReportController::class, 'updateStatus'])->name('damage_reports.update-status');
    });
});

require __DIR__.'/auth.php';
