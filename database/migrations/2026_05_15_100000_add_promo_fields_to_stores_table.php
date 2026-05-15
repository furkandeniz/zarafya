<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->tinyInteger('promo_discount')->unsigned()->nullable()->after('free_shipping_threshold');
            $table->string('promo_title', 100)->nullable()->after('promo_discount');
            $table->timestamp('promo_starts_at')->nullable()->after('promo_title');
            $table->timestamp('promo_ends_at')->nullable()->after('promo_starts_at');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['promo_discount', 'promo_title', 'promo_starts_at', 'promo_ends_at']);
        });
    }
};
