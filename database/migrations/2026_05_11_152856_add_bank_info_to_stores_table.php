<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('bank_name')->nullable()->after('address');
            $table->string('account_holder')->nullable()->after('bank_name');
            $table->string('iban', 32)->nullable()->after('account_holder');
            $table->string('account_number')->nullable()->after('iban');
            $table->string('branch_name')->nullable()->after('account_number');
            $table->string('branch_code', 20)->nullable()->after('branch_name');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'account_holder', 'iban', 'account_number', 'branch_name', 'branch_code']);
        });
    }
};
