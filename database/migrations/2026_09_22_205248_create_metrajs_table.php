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
        Schema::create('metrajs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('santiye_id')
                ->nullable()
                ->constrained('santiyes')
                ->nullOnDelete();

            $table->string('metraj_tipi');

            $table->decimal('uzunluk', 10, 3)->nullable();
            $table->decimal('genislik', 10, 3)->nullable();
            $table->decimal('kalinlik', 10, 3)->nullable();
            $table->decimal('yukseklik', 10, 3)->nullable();

            $table->unsignedInteger('kapi_sayisi')->nullable();
            $table->unsignedInteger('pencere_sayisi')->nullable();

            $table->decimal('fire_orani', 5, 2)->nullable();

            // Demir
            $table->decimal('demir_cap', 8, 3)->nullable();
            $table->unsignedInteger('demir_adet')->nullable();
            $table->decimal('demir_boy', 10, 3)->nullable();
            $table->decimal('demir_birim_agirlik', 8, 3)->nullable();

            // Hesaplanan sonuç
            $table->decimal('sonuc', 15, 3);

            $table->string('birim');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metrajs');
    }
};
