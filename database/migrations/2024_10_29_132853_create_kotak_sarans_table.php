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
        Schema::create('kotak_sarans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->string('nomor');
            $table->string('email');
            $table->text('pesan');
            $table->string('lampiran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kotak_sarans');
    }
};
