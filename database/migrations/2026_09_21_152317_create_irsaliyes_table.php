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
        Schema::create('irsaliyes', function (Blueprint $table) {
            $table->id();
            // İrsaliyeyi oluşturan kullanıcı
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // İlişkili şantiye
            $table->foreignId('santiye_id')
                ->constrained('santiyes')
                ->cascadeOnDelete();

            // İrsaliye fotoğrafı
            $table->string('irsaliye_fotografi')->nullable();

            // Malzeme bilgileri
            $table->string('malzeme_cinsi');
            $table->string('malzeme_adi');
            $table->decimal('malzeme_miktari', 10, 2);

            // Gönderen / tedarikçi
            $table->string('tedarikci');

            // Araç plakası
            $table->string('arac_plakasi');

            // İrsaliye tarihi
            $table->date('tarih');

            // Açıklama
            $table->text('not')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('irsaliyes');
    }
};
