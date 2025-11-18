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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('doc_path');
            $table->enum('type', ['umum','sk','sop','pmk','formulir'])->default('umum');
            $table->unsignedBigInteger('upload_by'); // admin id
            $table->boolean('status')->default(true);
            $table->timestamps();


            $table->foreign('upload_by')->references('id')->on('admins')->onDelete('cascade');
            });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
