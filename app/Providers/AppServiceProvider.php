<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (
            str_contains(request()->getHost(), 'ngrok') || 
            request()->header('x-forwarded-proto') === 'https' || 
            request()->server('HTTP_X_FORWARDED_PROTO') === 'https'
        ) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Dynamically load database configurations into Laravel config
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('app_configs')) {
                $dbConfigs = \App\Models\AppConfig::pluck('value', 'key')->all();
                foreach ($dbConfigs as $key => $val) {
                    // Map to both standard config keys and custom app namespace
                    config(['app.' . $key => $val]);
                    if ($key === 'app_name') {
                        config(['app.name' => $val]);
                    }
                }
            }
        } catch (\Exception $e) {
            // Silence exception during migration or setup
        }
    }
}
