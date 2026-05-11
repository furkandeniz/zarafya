<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite enum'u CHECK constraint olarak saklar, MODIFY COLUMN desteklenmez.
        // Çözüm: yeni TEXT kolonu ekle → veriyi kopyala → eskiyi sil → yeniden adlandır.
        DB::statement('PRAGMA foreign_keys = OFF');

        DB::statement("ALTER TABLE users ADD COLUMN role_new TEXT NOT NULL DEFAULT 'customer'");
        DB::statement('UPDATE users SET role_new = role');
        DB::statement('ALTER TABLE users DROP COLUMN role');
        DB::statement('ALTER TABLE users RENAME COLUMN role_new TO role');

        DB::statement('PRAGMA foreign_keys = ON');
    }

    public function down(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF');

        DB::statement("ALTER TABLE users ADD COLUMN role_old TEXT NOT NULL DEFAULT 'customer'");
        DB::statement("UPDATE users SET role_old = CASE WHEN role IN ('admin','seller','customer') THEN role ELSE 'customer' END");
        DB::statement('ALTER TABLE users DROP COLUMN role');
        DB::statement('ALTER TABLE users RENAME COLUMN role_old TO role');

        DB::statement('PRAGMA foreign_keys = ON');
    }
};
