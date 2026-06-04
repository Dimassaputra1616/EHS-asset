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

    // API Services (Search & Notifications remain globally available to auth users)
    Route::get('api/notifications', [AssetController::class, 'getNotifications'])->name('api.notifications');
    Route::get('api/search', [AssetController::class, 'globalSearch'])->name('api.search');
    Route::get('api/assets/by-code/{code}', [AssetController::class, 'getByCode'])->name('api.assets.by-code');

    // Assets (Protected by permissions)
    Route::middleware('permission:assets.view')->group(function () {
        Route::get('assets/export', [AssetController::class, 'export'])->name('assets.export');
        Route::get('assets', [AssetController::class, 'index'])->name('assets.index');
    });
    Route::middleware('permission:assets.create')->group(function () {
        Route::get('assets/generate-code/{category_id}', [AssetController::class, 'generateCode'])->name('assets.generate-code');
        Route::get('assets/create', [AssetController::class, 'create'])->name('assets.create');
        Route::post('assets', [AssetController::class, 'store'])->name('assets.store');
    });
    Route::middleware('permission:assets.edit')->group(function () {
        Route::get('assets/{asset}/edit', [AssetController::class, 'edit'])->name('assets.edit');
        Route::match(['put', 'patch'], 'assets/{asset}', [AssetController::class, 'update'])->name('assets.update');
    });
    Route::middleware('permission:assets.delete')->group(function () {
        Route::delete('assets/{asset}', [AssetController::class, 'destroy'])->name('assets.destroy');
    });
    Route::middleware('permission:assets.view')->group(function () {
        Route::get('assets/{asset}', [AssetController::class, 'show'])->name('assets.show');
    });

    // Consumables (Protected by permissions)
    Route::middleware('permission:consumables.view')->group(function () {
        Route::get('consumables/export', [ConsumableController::class, 'export'])->name('consumables.export');
        Route::get('consumables', [ConsumableController::class, 'index'])->name('consumables.index');
        Route::get('consumables/transactions/in', [ConsumableTransactionController::class, 'indexIn'])->name('consumables.transactions.in');
        Route::get('consumables/transactions/out', [ConsumableTransactionController::class, 'indexOut'])->name('consumables.transactions.out');
    });
    Route::middleware('permission:consumables.create')->group(function () {
        Route::get('consumables/generate-code/{category_id}', [ConsumableController::class, 'generateCode'])->name('consumables.generate-code');
        Route::get('consumables/create', [ConsumableController::class, 'create'])->name('consumables.create');
        Route::post('consumables', [ConsumableController::class, 'store'])->name('consumables.store');
        Route::get('consumables/transactions/create', [ConsumableTransactionController::class, 'create'])->name('consumables.transactions.create');
        Route::post('consumables/transactions', [ConsumableTransactionController::class, 'store'])->name('consumables.transactions.store');
    });
    Route::middleware('permission:consumables.edit')->group(function () {
        Route::get('consumables/{consumable}/edit', [ConsumableController::class, 'edit'])->name('consumables.edit');
        Route::match(['put', 'patch'], 'consumables/{consumable}', [ConsumableController::class, 'update'])->name('consumables.update');
    });
    Route::middleware('permission:consumables.delete')->group(function () {
        Route::delete('consumables/{consumable}', [ConsumableController::class, 'destroy'])->name('consumables.destroy');
    });
    Route::middleware('permission:consumables.view')->group(function () {
        Route::get('consumables/{consumable}', [ConsumableController::class, 'show'])->name('consumables.show');
    });

    // Stock Opnames & Master Data (Protected by permissions)
    Route::middleware('permission:master.manage')->group(function () {
        Route::resource('stock-opnames', StockOpnameController::class);
        Route::get('categories/{category}/items', [CategoryController::class, 'items'])->name('categories.items');
        Route::resource('categories', CategoryController::class);
        Route::resource('locations', LocationController::class);
        Route::resource('suppliers', SupplierController::class);
    });

    // Staff Request & Reporting Portals (Protected by specific permissions)
    Route::prefix('staff')->name('staff.')->group(function () {
        // Asset Requests
        Route::middleware('permission:requests.view')->group(function () {
            Route::get('/requests', [\App\Http\Controllers\Staff\AssetRequestController::class, 'index'])->name('requests.index');
        });
        Route::middleware('permission:requests.create')->group(function () {
            Route::get('/requests/create', [\App\Http\Controllers\Staff\AssetRequestController::class, 'create'])->name('requests.create');
            Route::post('/requests', [\App\Http\Controllers\Staff\AssetRequestController::class, 'store'])->name('requests.store');
        });

        // Damage/Incident Reports
        Route::middleware('permission:damage_reports.view')->group(function () {
            Route::get('/damage-reports', [\App\Http\Controllers\Staff\DamageReportController::class, 'index'])->name('damage_reports.index');
        });
        Route::middleware('permission:damage_reports.create')->group(function () {
            Route::get('/damage-reports/create', [\App\Http\Controllers\Staff\DamageReportController::class, 'create'])->name('damage_reports.create');
            Route::post('/damage-reports', [\App\Http\Controllers\Staff\DamageReportController::class, 'store'])->name('damage_reports.store');
        });
    });

    // Administration (Protected by specific permissions)
    Route::prefix('admin')->name('admin.')->group(function () {
        // Config Master
        Route::middleware('permission:config.manage')->group(function () {
            Route::get('/configs', [AppConfigController::class, 'index'])->name('configs.index');
            Route::put('/configs', [AppConfigController::class, 'update'])->name('configs.update');
        });
        
        // Users
        Route::middleware('permission:users.manage')->group(function () {
            Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
            Route::put('users/{user}/role', [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.update-role');
        });

        // Roles
        Route::middleware('permission:roles.manage')->group(function () {
            Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
        });

        // Activity Logs
        Route::middleware('role:admin')->group(function () {
            Route::get('/logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('logs.index');
            Route::delete('/logs/clear', [\App\Http\Controllers\Admin\ActivityLogController::class, 'clearAll'])->name('logs.clear');
            Route::delete('/logs/{id}', [\App\Http\Controllers\Admin\ActivityLogController::class, 'destroy'])->name('logs.destroy');
        });

        // Manage Asset Requests
        Route::middleware('permission:requests.manage')->group(function () {
            Route::get('/requests', [\App\Http\Controllers\Admin\ManageRequestController::class, 'index'])->name('requests.index');
            Route::put('/requests/{id}/status', [\App\Http\Controllers\Admin\ManageRequestController::class, 'updateStatus'])->name('requests.update-status');
            Route::delete('/requests/{id}', [\App\Http\Controllers\Admin\ManageRequestController::class, 'destroy'])->name('requests.destroy');
        });

        // Manage Damage Reports
        Route::middleware('permission:damage_reports.manage')->group(function () {
            Route::get('/damage-reports', [\App\Http\Controllers\Admin\ManageDamageReportController::class, 'index'])->name('damage_reports.index');
            Route::put('/damage-reports/{id}/status', [\App\Http\Controllers\Admin\ManageDamageReportController::class, 'updateStatus'])->name('damage_reports.update-status');
            Route::delete('/damage-reports/{id}', [\App\Http\Controllers\Admin\ManageDamageReportController::class, 'destroy'])->name('damage_reports.destroy');
        });
    });
});

require __DIR__.'/auth.php';
