<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('m_level', function (Blueprint $table) {
            $table->id('level_id'); // hanya satu auto increment
            $table->string('level_kode', 10); // kode level (varchar)
            $table->string('level_nama', 100); // nama level (varchar)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_level');
    }
};
