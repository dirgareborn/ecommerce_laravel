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
        Schema::create('virtual_tours', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('room_id');
            $table->string('tour_type');
            $table->string('tour_url');
            $table->text('thumbnail')->nullable();
            $table->boolean('status')->default(1); // 1=aktif, 0=nonaktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_tours');
    }
};
