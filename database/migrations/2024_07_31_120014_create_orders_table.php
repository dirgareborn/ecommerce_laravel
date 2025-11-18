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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
			$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
			$table->string('coupon_code')->nullable();
			$table->decimal('coupon_amount', 15, 2)->nullable();
            $table->enum('order_status', ['waiting','approved','rejected','cancelled','completed'])->default('waiting');
			$table->enum('payment_status', ['unpaid','paid','failed','refunded'])->default('unpaid');
            $table->enum('payment_method', ['transfer','QRIS'])->default('transfer');
			$table->text('payment_proof')->nullable();
			$table->decimal('grand_total', 15, 2)->default(0);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
