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
        Schema::create('damage_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('asset_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('consumable_id')->nullable()->constrained()->onDelete('set null');
            $table->string('item_name')->nullable();
            $table->text('description');
            $table->string('photo')->nullable();
            $table->string('urgency')->default('medium'); // 'low', 'medium', 'high'
            $table->string('status')->default('pending'); // 'pending', 'investigating', 'resolved', 'closed'
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damage_reports');
    }
};
