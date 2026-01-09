<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('m_user', function (Blueprint $table) {
        // Menambahkan kolom avatar setelah password, nullable (boleh kosong)
        $table->string('avatar')->nullable()->after('password');
    });
}

public function down(): void
{
    Schema::table('m_user', function (Blueprint $table) {
        $table->dropColumn('avatar');
    });
}
};
