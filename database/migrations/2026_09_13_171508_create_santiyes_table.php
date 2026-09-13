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
        Schema::create('santiyes', function (Blueprint $table) {
            $table->id();
            $table->string('firma_adi');
            $table->string('santiye_adi');
            $table->dateTime('baslangic_zamani'); 
            $table->dateTime('bitis_zamani');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('santiyes');
    }
};
