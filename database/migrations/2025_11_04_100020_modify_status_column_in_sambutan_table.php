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
        Schema::table('sambutan', function (Blueprint $table) {
            // Ubah kolom status dari enum ke string
            $table->string('status', 20)->default('Aktif')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sambutan', function (Blueprint $table) {
            // Kembalikan ke enum
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif')->change();
        });
    }
};
