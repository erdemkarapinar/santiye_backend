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
        Schema::create('malzeme_stogus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('santiye_id')
                ->constrained('santiyes')
                ->cascadeOnDelete();

            $table->string('malzeme_adi');

            $table->string('birim');

            $table->decimal('miktar', 10, 2);

            $table->decimal('min_stok', 10, 2);

            $table->decimal('birim_fiyat', 12, 2);

            $table->text('not')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('malzeme_stogus');
    }
};
