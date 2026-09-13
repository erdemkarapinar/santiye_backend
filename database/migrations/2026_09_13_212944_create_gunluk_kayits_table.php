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
        Schema::create('gunluk_kayits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('santiye_id')
                ->constrained('santiyes')
                ->cascadeOnDelete();
            $table->string('weather')->nullable();
            $table->decimal('temperature', 5, 2)->nullable();
            $table->string('not');
            $table->string('ekipman');
            $table->unsignedDecimal('toplam ücret', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gunluk_kayits');
    }
};
