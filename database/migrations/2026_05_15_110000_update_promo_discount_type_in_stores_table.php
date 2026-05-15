<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // promo_discount tinyInteger → decimal olarak yeniden oluştur
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('promo_discount');
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->decimal('promo_discount', 10, 2)->unsigned()->nullable()->after('free_shipping_threshold');
            $table->string('promo_discount_type', 10)->default('percent')->after('promo_discount');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['promo_discount_type']);
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('promo_discount');
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->tinyInteger('promo_discount')->unsigned()->nullable()->after('free_shipping_threshold');
        });
    }
};
