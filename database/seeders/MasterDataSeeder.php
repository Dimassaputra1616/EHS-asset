<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Location;
use App\Models\Supplier;
use App\Models\Asset;
use App\Models\Consumable;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ----------------------------------------------------
        // 1. SEED CATEGORIES (EHS SPECIFIC)
        // ----------------------------------------------------
        // Assets
        $catHt = Category::firstOrCreate(['name' => 'HT (Handy Talky)', 'type' => 'asset']);
        $catApar = Category::firstOrCreate(['name' => 'APAR (Alat Pemadam)', 'type' => 'asset']);
        $catHydrant = Category::firstOrCreate(['name' => 'Hydrant Equipment', 'type' => 'asset']);
        
        // Consumables / PPE
        $catHelm = Category::firstOrCreate(['name' => 'Helm Safety', 'type' => 'consumable']);
        $catP3k = Category::firstOrCreate(['name' => 'P3K (First Aid)', 'type' => 'consumable']);
        $catAtk = Category::firstOrCreate(['name' => 'ATK Safety & Gudang', 'type' => 'consumable']);
        $catSafety = Category::firstOrCreate(['name' => 'Safety Equipments', 'type' => 'consumable']);
        $catSepatu = Category::firstOrCreate(['name' => 'Sepatu Safety', 'type' => 'consumable']);

        // ----------------------------------------------------
        // 2. SEED LOCATIONS (EHS WAREHOUSE)
        // ----------------------------------------------------
        $locGudang = Location::firstOrCreate([
            'name' => 'Gudang Utama EHS', 
            'building' => 'Gedung A', 
            'floor' => '1'
        ]);
        $locOffice = Location::firstOrCreate([
            'name' => 'Office EHS', 
            'building' => 'Gedung B', 
            'floor' => '2'
        ]);
        $locSecurity = Location::firstOrCreate([
            'name' => 'Pos Security Utama', 
            'building' => 'Pos 1', 
            'floor' => '1'
        ]);

        // ----------------------------------------------------
        // 3. SEED SUPPLIERS
        // ----------------------------------------------------
        $supSafety = Supplier::firstOrCreate([
            'name' => 'PT Safe Guard Indonesia',
            'contact_person' => 'Budi Santoso',
            'email' => 'sales@safeguard.co.id',
            'phone' => '021-5556789',
            'address' => 'Kawasan Industri Jababeka, Bekasi'
        ]);
        $supAlkes = Supplier::firstOrCreate([
            'name' => 'CV Medika Jaya',
            'contact_person' => 'Siti Aminah',
            'email' => 'marketing@medikajaya.com',
            'phone' => '021-8881234',
            'address' => 'Jl. Salemba Raya No. 45, Jakarta Pusat'
        ]);

        // ----------------------------------------------------
        // 4. SEED DETAILED CONSUMABLES (P3K, ATK, HELM, SAFETY)
        // ----------------------------------------------------
        // Helm
        $helms = ['Helm Merah', 'Helm Biru', 'Helm Hijau', 'Helm Orange'];
        foreach ($helms as $index => $helm) {
            Consumable::firstOrCreate(
                ['name' => $helm, 'category_id' => $catHelm->id],
                [
                    'code' => sprintf('HSE-HLM-%03d', $index + 1),
                    'supplier_id' => $supSafety->id,
                    'unit' => 'Pcs',
                    'stock' => rand(15, 30),
                    'min_stock' => 5,
                    'description' => 'Helm pelindung kepala K3'
                ]
            );
        }

        // P3K Items
        $p3kItems = [
            'Kasa Steril' => ['roll', 50, 10],
            'Perban 10Cm' => ['Pcs', 40, 10],
            'Perban 5Cm' => ['Pcs', 40, 10],
            'Aquades' => ['Botol', 15, 5],
            'NACL' => ['Botol', 20, 5],
            'Bethadin' => ['Botol', 25, 5],
            'Kapas 25g' => ['Pack', 30, 8],
            'Gunting Lipat' => ['Pcs', 10, 2],
            'Pinset' => ['Pcs', 12, 2],
            'Mitella' => ['Pcs', 15, 3],
            'Peniti' => ['Pack', 8, 2],
            'Plaster Cepat' => ['Box', 20, 5],
            'Plaster Roll' => ['Box', 15, 4],
            'Masker Medis' => ['Box', 35, 10],
            'Sarung Tangan Medis' => ['Box', 25, 5],
            'Kantong Plastik' => ['Pack', 10, 2],
            'Gelas Cuci Mata' => ['Pcs', 8, 2],
            'Senter Kecil' => ['Pcs', 14, 3],
        ];
        $index = 1;
        foreach ($p3kItems as $name => $data) {
            Consumable::firstOrCreate(
                ['name' => $name, 'category_id' => $catP3k->id],
                [
                    'code' => sprintf('HSE-P3K-%03d', $index++),
                    'supplier_id' => $supAlkes->id,
                    'unit' => $data[0],
                    'stock' => $data[1],
                    'min_stock' => $data[2],
                    'description' => 'Peralatan pertolongan pertama pada kecelakaan (P3K)'
                ]
            );
        }

        // ATK Safety
        $atkItems = [
            'Lakban Merah' => ['Roll', 25, 5],
            'Lakban Kuning' => ['Roll', 25, 5],
            'Lakban Kertas' => ['Roll', 30, 5],
            'Safety Line' => ['Roll', 12, 3],
            'Safety Line Anti Slip' => ['Roll', 8, 2],
            'Lakban Safety Line' => ['Roll', 15, 3],
            '3M Gel' => ['Pcs', 20, 5],
            'Pulpen OPF' => ['Box', 5, 1],
            'Bendera K3' => ['Pcs', 10, 2],
            'Rompi EHS' => ['Pcs', 45, 10],
            'Cat Hijau' => ['Kaleng', 15, 3],
            'Cat Merah' => ['Kaleng', 15, 3],
        ];
        $index = 1;
        foreach ($atkItems as $name => $data) {
            Consumable::firstOrCreate(
                ['name' => $name, 'category_id' => $catAtk->id],
                [
                    'code' => sprintf('HSE-STA-%03d', $index++),
                    'supplier_id' => $supSafety->id,
                    'unit' => $data[0],
                    'stock' => $data[1],
                    'min_stock' => $data[2],
                    'description' => 'Kebutuhan ATK Marka dan Rambu Gudang EHS'
                ]
            );
        }

        // Safety Equipment Consumables
        $safetyItems = [
            'Sarung Tangan' => ['Pasang', 100, 20],
            'Sarung Tangan Latex' => ['Pasang', 80, 15],
            'Cartridge Masker' => ['Box', 20, 5],
            'Respirator Masker' => ['Pcs', 15, 3],
            'Ear Muff' => ['Pcs', 18, 4],
            'Bidai' => ['Set', 5, 1],
            'Kursi Roda' => ['Pcs', 3, 1],
        ];
        $index = 1;
        foreach ($safetyItems as $name => $data) {
            Consumable::firstOrCreate(
                ['name' => $name, 'category_id' => $catSafety->id],
                [
                    'code' => sprintf('HSE-SFT-%03d', $index++),
                    'supplier_id' => $supSafety->id,
                    'unit' => $data[0],
                    'stock' => $data[1],
                    'min_stock' => $data[2],
                    'description' => 'Peralatan keselamatan kerja standar K3'
                ]
            );
        }

        // Sepatu Safety
        $sepatuItems = [
            'Sepatu Safety Baru' => ['Pasang', 25, 5],
            'Sepatu Safety Bekas' => ['Pasang', 14, 2],
        ];
        $index = 1;
        foreach ($sepatuItems as $name => $data) {
            Consumable::firstOrCreate(
                ['name' => $name, 'category_id' => $catSepatu->id],
                [
                    'code' => sprintf('HSE-SPT-%03d', $index++),
                    'supplier_id' => $supSafety->id,
                    'unit' => $data[0],
                    'stock' => $data[1],
                    'min_stock' => $data[2],
                    'description' => 'Alas kaki safety pelindung kerja'
                ]
            );
        }

        // ----------------------------------------------------
        // 5. SEED DETAILED ASSETS (HT, APAR, HYDRANT)
        // ----------------------------------------------------
        // HT (Handy Talky 1-7)
        for ($i = 1; $i <= 7; $i++) {
            Asset::firstOrCreate(
                ['code' => "AST-HT-00$i"],
                [
                    'name' => "Handy Talky (HT $i)",
                    'category_id' => $catHt->id,
                    'location_id' => $locSecurity->id,
                    'supplier_id' => $supSafety->id,
                    'condition' => 'Good',
                    'status' => 'In Use',
                    'purchase_date' => now()->subMonths(6),
                    'purchase_cost' => 750000.00,
                    'description' => "Radio Komunikasi Pos Keamanan EHS $i"
                ]
            );
        }

        // APAR (Powder, Co2, Foam, AF11)
        $apars = [
            ['APAR Powder 6Kg', 'Powder'],
            ['APAR Co2 5Kg', 'Co2'],
            ['APAR Foam 9Ltr', 'Foam'],
            ['APAR AF11 4Kg', 'AF11']
        ];
        foreach ($apars as $index => $apar) {
            // Good condition APAR
            Asset::firstOrCreate(
                ['code' => "AST-APR-00" . ($index + 1)],
                [
                    'name' => $apar[0] . " - Gudang",
                    'category_id' => $catApar->id,
                    'location_id' => $locGudang->id,
                    'supplier_id' => $supSafety->id,
                    'condition' => 'Good',
                    'status' => 'In Use',
                    'purchase_date' => now()->subYear(),
                    'purchase_cost' => 1200000.00,
                    'description' => "Tabung pemadam kebakaran jenis " . $apar[1]
                ]
            );

            // Refill needed (Low pressure) APAR
            Asset::firstOrCreate(
                ['code' => "AST-APR-LW0" . ($index + 1)],
                [
                    'name' => $apar[0] . " - Refill Needed",
                    'category_id' => $catApar->id,
                    'location_id' => $locGudang->id,
                    'supplier_id' => $supSafety->id,
                    'condition' => 'Poor',
                    'status' => 'Maintenance',
                    'purchase_date' => now()->subYear(),
                    'purchase_cost' => 1200000.00,
                    'description' => "Tabung pemadam kebakaran jenis " . $apar[1] . " (Butuh Refill)"
                ]
            );
        }

        // APAR Signages
        Asset::firstOrCreate(
            ['code' => 'AST-SGN-2D'],
            [
                'name' => 'Sign APAR 2D',
                'category_id' => $catApar->id,
                'location_id' => $locGudang->id,
                'supplier_id' => $supSafety->id,
                'condition' => 'Good',
                'status' => 'In Stock',
                'purchase_date' => now(),
                'purchase_cost' => 85000.00,
                'description' => 'Rambu petunjuk APAR Dua Dimensi'
            ]
        );
        Asset::firstOrCreate(
            ['code' => 'AST-SGN-3D'],
            [
                'name' => 'Sign APAR 3D',
                'category_id' => $catApar->id,
                'location_id' => $locGudang->id,
                'supplier_id' => $supSafety->id,
                'condition' => 'Good',
                'status' => 'In Stock',
                'purchase_date' => now(),
                'purchase_cost' => 135000.00,
                'description' => 'Rambu petunjuk APAR Tiga Dimensi'
            ]
        );

        // Hydrant Equipment
        $hydrants = [
            ['Selang Hydrant Canvas 1.5 Inch', 'AST-HYD-SL15', 'New'],
            ['Selang Hydrant Canvas 2.5 Inch', 'AST-HYD-SL25', 'Existing'],
            ['Nozzle Hydrant Kuningan 1.5 Inch', 'AST-HYD-NZ15', 'New'],
            ['Nozzle Hydrant Kuningan 2.5 Inch', 'AST-HYD-NZ25', 'Existing'],
        ];
        foreach ($hydrants as $hyd) {
            Asset::firstOrCreate(
                ['code' => $hyd[1]],
                [
                    'name' => $hyd[0] . " (" . $hyd[2] . ")",
                    'category_id' => $catHydrant->id,
                    'location_id' => $locGudang->id,
                    'supplier_id' => $supSafety->id,
                    'condition' => 'Good',
                    'status' => 'In Use',
                    'purchase_date' => now()->subMonths(3),
                    'purchase_cost' => 2500000.00,
                    'description' => "Perlengkapan hydrant pemadam kebakaran gudang"
                ]
            );
        }
    }
}
