<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            if (!Schema::hasColumn('stores', 'shipping_type')) {
                $table->enum('shipping_type', ['free', 'paid', 'free_above'])->default('paid')->after('is_active');
            }
            if (!Schema::hasColumn('stores', 'shipping_cost')) {
                $table->decimal('shipping_cost', 10, 2)->nullable()->after('shipping_type');
            }
            if (!Schema::hasColumn('stores', 'free_shipping_threshold')) {
                $table->decimal('free_shipping_threshold', 10, 2)->nullable()->after('shipping_cost');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['shipping_type', 'shipping_cost', 'free_shipping_threshold']);
        });
    }
};
