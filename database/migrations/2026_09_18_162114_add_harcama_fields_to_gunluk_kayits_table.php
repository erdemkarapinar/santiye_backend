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
        Schema::table('gunluk_kayits', function (Blueprint $table) {
            Schema::table('gunluk_kayits', function (Blueprint $table) {
            $table->string('harcama_kategori')->nullable()->after('ekipman');
            $table->text('harcama_aciklama')->nullable()->after('harcama_kategori');
            $table->decimal('harcama_tutar', 12, 2)->nullable()->after('harcama_aciklama');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gunluk_kayits', function (Blueprint $table) {
            $table->dropColumn([
                'harcama_kategori',
                'harcama_aciklama',
                'harcama_tutar',
            ]);
        });
    }
};
