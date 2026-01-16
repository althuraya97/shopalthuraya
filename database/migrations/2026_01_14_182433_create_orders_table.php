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
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('address'); // الشارع والمنزل
        $table->string('apartment')->nullable();
        $table->string('city');
        $table->string('state')->nullable();
        $table->string('country');
        $table->string('zip_code');
        $table->string('payment_method')->default('الدفع عند الاستلام');
        $table->decimal('total_price', 10, 2);
        $table->string('status')->default('قيد المعالجة');
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
