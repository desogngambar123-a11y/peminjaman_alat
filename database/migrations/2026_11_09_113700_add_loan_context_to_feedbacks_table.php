<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('feedbacks', 'loan_id')) {
            Schema::table('feedbacks', function (Blueprint $table) {
                $table->foreignId('loan_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('peminjamans')
                    ->cascadeOnDelete();
            });
        }

        if (!Schema::hasColumn('feedbacks', 'role')) {
            Schema::table('feedbacks', function (Blueprint $table) {
                $table->string('role')->nullable()->after('user');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('feedbacks', 'loan_id')) {
            Schema::table('feedbacks', function (Blueprint $table) {
                $table->dropConstrainedForeignId('loan_id');
            });
        }

        if (Schema::hasColumn('feedbacks', 'role')) {
            Schema::table('feedbacks', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};
