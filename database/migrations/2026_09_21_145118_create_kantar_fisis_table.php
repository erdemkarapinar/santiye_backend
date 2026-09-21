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
        Schema::create('kantar_fisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('santiye_id')
                ->constrained('santiyes')
                ->cascadeOnDelete();

            $table->string('fis_fotografi')->nullable();

            $table->string('urun_cinsi');

            $table->string('tedarikci');

            $table->decimal('giris_kg', 10, 2);

            $table->decimal('cikis_kg', 10, 2);

            $table->decimal('net_agirlik', 10, 2);

            $table->date('tarih');

            $table->text('not')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kantar_fisis');
    }
};
