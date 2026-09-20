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
        Schema::create('beton_fisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('santiye_id')
                ->constrained('santiyes')
                ->cascadeOnDelete();

            $table->string('beton_fotosu')->nullable();

            $table->string('irsaliye_fotosu')->nullable();

            $table->enum('beton_sinifi', [
                'C16',
                'C20',
                'C25',
                'C30',
                'C35',
                'C40',
                'C45'
            ]);

            $table->string('tedarikci');

            $table->decimal('miktar', 10, 2);

            $table->string('arac_plakasi');

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
        Schema::dropIfExists('beton_fisis');
    }
};
