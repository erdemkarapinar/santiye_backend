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
        Schema::create('hatali_imalats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('santiye_id')
                ->constrained('santiyes')
                ->cascadeOnDelete();

            $table->string('baslik');

            $table->text('aciklama')->nullable();

            $table->string('konum')->nullable();

            $table->enum('durum', [
                'acik',
                'devam_ediyor',
                'kapali',
            ])->default('acik');

            $table->string('fotograf')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hatali_imalats');
    }
};
