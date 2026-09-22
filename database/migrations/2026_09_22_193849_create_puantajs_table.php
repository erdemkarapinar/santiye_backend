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
        Schema::create('puantajs', function (Blueprint $table) {
            $table->id();
            // Çalışan personel
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Personelin çalıştığı şantiye
            $table->foreignId('santiye_id')
                ->constrained('santiyes')
                ->cascadeOnDelete();

            // Puantajı oluşturan kullanıcı
            $table->foreignId('olusturan_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Çalışılan gün
            $table->date('tarih');

            $table->timestamps();

            // Aynı personel aynı şantiyeye aynı gün
            // iki kez yazılmasın.
            $table->unique([
                'user_id',
                'santiye_id',
                'tarih'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puantajs');
    }
};
