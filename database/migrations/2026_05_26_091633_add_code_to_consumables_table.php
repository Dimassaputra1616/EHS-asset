<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('consumables', function (Blueprint $table) {
            $table->string('code')->nullable()->unique()->after('id');
        });

        // Populate existing consumables
        $consumables = \DB::table('consumables')->get();
        foreach ($consumables as $item) {
            $category = \DB::table('categories')->find($item->category_id);
            $name = $category ? $category->name : 'CNS';
            
            // Clean prefix extraction
            preg_match('/^([^\(]+)/', $name, $matches);
            $cleanName = trim($matches[1] ?? $name);
            
            $words = explode(' ', $cleanName);
            if (count($words) >= 2) {
                $prefix = '';
                foreach ($words as $w) {
                    if (!empty($w)) {
                        $prefix .= substr($w, 0, 1);
                    }
                }
            } else {
                $prefix = substr($cleanName, 0, 3);
            }
            $prefix = strtoupper(trim($prefix));
            
            // Specific overrides for accuracy
            if (str_contains(strtolower($name), 'helm')) $prefix = 'HLM';
            if (str_contains(strtolower($name), 'p3k') || str_contains(strtolower($name), 'first aid')) $prefix = 'P3K';
            if (str_contains(strtolower($name), 'atk')) $prefix = 'ATK';
            if (str_contains(strtolower($name), 'safety')) $prefix = 'SFT';
            if (str_contains(strtolower($name), 'sepatu')) $prefix = 'SPT';

            $seq = 1;
            while (true) {
                $code = sprintf("HSE-%s-%03d", $prefix, $seq);
                // Check if this code was already assigned in database OR is about to be assigned
                $exists = \DB::table('consumables')->where('code', $code)->exists();
                if (!$exists) {
                    break;
                }
                $seq++;
            }
            
            \DB::table('consumables')->where('id', $item->id)->update(['code' => $code]);
        }

        // Make the code column non-nullable
        Schema::table('consumables', function (Blueprint $table) {
            $table->string('code')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consumables', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
};
