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
        Schema::create('tiket_permintaans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('permintaan_id');
            $table->integer('worker');
            $table->integer('pemberi');
            $table->string('masukan');
            $table->integer('status')->default(0);
            $table->date('jam_mulai')->nullable();
            $table->date('jam_selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiket_permintaans');
    }
};
