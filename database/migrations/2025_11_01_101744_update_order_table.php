<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('payment_proof_uploaded_at')->nullable()->after('payment_proof');
            $table->string('payment_proof_uploaded_by')->nullable()->after('payment_proof_uploaded_at');
            $table->json('payment_proof_meta')->nullable()->after('payment_proof_uploaded_by');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_proof_uploaded_at',
                'payment_proof_uploaded_by',
                'payment_proof_meta',
            ]);
        });
    }
};

