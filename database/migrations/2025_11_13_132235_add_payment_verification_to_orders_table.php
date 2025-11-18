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
        Schema::table('orders', function (Blueprint $table) {
               // admin yang melakukan verifikasi pembayaran
            $table->unsignedBigInteger('verified_by_admin_id')->nullable()->after('payment_status');
            // waktu verifikasi
            $table->timestamp('payment_verified_at')->nullable()->after('verified_by_admin_id');
            // opsional: catatan verifikator
            $table->string('payment_verifier_note')->nullable()->after('payment_verified_at');

            $table->foreign('verified_by_admin_id')
                  ->references('id')->on('admins')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['verified_by_admin_id']);
            $table->dropColumn(['verified_by_admin_id', 'payment_verified_at', 'payment_verifier_note']);
        });
    }
};
