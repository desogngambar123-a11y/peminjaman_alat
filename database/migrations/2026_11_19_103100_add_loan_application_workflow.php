<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->string('status')->default('Menunggu Verifikasi')->change();
            $table->text('catatan_admin')->nullable();
        });
    }

    public function down(): void
    {
        DB::table('peminjamans')
            ->whereIn('status', ['Menunggu Verifikasi', 'Ditolak', 'Disetujui oleh Admin'])
            ->update(['status' => 'Dipinjam']);

        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn('catatan_admin');
            $table->enum('status', ['Dipinjam', 'Dikembalikan', 'Terlambat'])
                ->default('Dipinjam')
                ->change();
        });
    }
};
