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
        Schema::create('referanslar', function (Blueprint $table) {
            $table->id();
                        // Referansı oluşturan kullanıcı
            $table->foreignId('olusturan_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Referansın kullanılacağı şantiye
            $table->foreignId('santiye_id')
                ->constrained('santiyes')
                ->cascadeOnDelete();

            // Referans ile verilecek rol
            $table->foreignId('role_id')
                ->constrained('roles')
                ->cascadeOnDelete();

            // Sistem tarafından otomatik oluşturulacak kod
            $table->string('kod')->unique();

            // Referans kullanıldı mı?
            $table->boolean('kullanildi_mi')->default(false);

            // Kullanıldığında hangi kullanıcı kullandı?
            $table->foreignId('kullanan_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Referans aktif/pasif
            $table->boolean('aktif')->default(true);

            // İleride referansın son kullanma tarihi için
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referanslar');
    }
};
