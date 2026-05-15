<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedTinyInteger('min_shipping_days')->nullable()->after('stock');
            $table->unsignedTinyInteger('max_shipping_days')->nullable()->after('min_shipping_days');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['min_shipping_days', 'max_shipping_days']);
        });
    }
};
