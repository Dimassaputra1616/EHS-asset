<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AppConfig;

class AppConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
        $configs = [
            [
                'key' => 'app_name',
                'value' => 'HSE Asset Management',
                'label' => 'Application Name',
                'group' => 'general',
            ],
            [
                'key' => 'company_name',
                'value' => 'HSE Guard Corp',
                'label' => 'Company Name',
                'group' => 'general',
            ],
            [
                'key' => 'app_logo',
                'value' => null,
                'label' => 'Application Logo',
                'group' => 'general',
            ],
            [
                'key' => 'low_stock_threshold',
                'value' => '10',
                'label' => 'Low Stock Threshold',
                'group' => 'general',
            ],
            [
                'key' => 'asset_code_prefix',
                'value' => 'AST',
                'label' => 'Asset Code Prefix',
                'group' => 'general',
            ],
            [
                'key' => 'consumable_code_prefix',
                'value' => 'CSM',
                'label' => 'Consumable Code Prefix',
                'group' => 'general',
            ],
            [
                'key' => 'currency_symbol',
                'value' => 'Rp',
                'label' => 'Currency Symbol',
                'group' => 'general',
            ],
            [
                'key' => 'copyright_text',
                'value' => '© 2026 HSE Guard Corp — v1.0',
                'label' => 'Copyright Text',
                'group' => 'general',
            ],
            [
                'key' => 'primary_color',
                'value' => '#C0392B',
                'label' => 'Primary Color (HSE Red)',
                'group' => 'appearance',
            ],
            [
                'key' => 'sidebar_theme',
                'value' => 'Dark',
                'label' => 'Sidebar Theme Style',
                'group' => 'appearance',
            ],
            [
                'key' => 'glassmorphism_effects',
                'value' => '1',
                'label' => 'Enable Glassmorphism Effects',
                'group' => 'appearance',
            ],
            [
                'key' => 'show_sidebar_logo',
                'value' => '1',
                'label' => 'Show Sidebar Logo',
                'group' => 'appearance',
            ],
            [
                'key' => 'login_bg',
                'value' => 'images/auth/welcome-bg.png',
                'label' => 'Login Background Image',
                'group' => 'appearance',
            ],
        ];

        foreach ($configs as $config) {
            AppConfig::firstOrCreate(['key' => $config['key']], $config);
        }
    }
}
