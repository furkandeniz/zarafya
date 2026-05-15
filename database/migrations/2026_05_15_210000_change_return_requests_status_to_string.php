<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite enum CHECK constraint'ini kaldırıp plain string'e çevirmek için
        // tabloyu yeniden oluşturuyoruz (SQLite ALTER COLUMN desteklemiyor).
        DB::statement('CREATE TABLE return_requests_new (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            order_id INTEGER NOT NULL,
            user_id INTEGER,
            reason VARCHAR NOT NULL,
            note TEXT,
            status VARCHAR NOT NULL DEFAULT \'pending\',
            admin_note TEXT,
            customer_reply TEXT,
            resolved_at DATETIME,
            created_at DATETIME,
            updated_at DATETIME,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        )');

        DB::statement('INSERT INTO return_requests_new SELECT * FROM return_requests');
        DB::statement('DROP TABLE return_requests');
        DB::statement('ALTER TABLE return_requests_new RENAME TO return_requests');
    }

    public function down(): void
    {
        // Geri almak için CHECK constraint'i geri eklemek gerekir;
        // veri kaybını önlemek adına down() işlevsiz bırakıldı.
    }
};
