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
                'key' => 'primary_color',
                'value' => '#C0392B',
                'label' => 'Primary Color (HSE Red)',
                'group' => 'appearance',
            ],
        ];

        foreach ($configs as $config) {
            AppConfig::firstOrCreate(['key' => $config['key']], $config);
        }
    }
}
