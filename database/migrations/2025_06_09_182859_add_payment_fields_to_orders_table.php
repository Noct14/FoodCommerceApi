<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('pickup_code')->nullable()->unique()->after('status');
            $table->string('payment_method')->nullable()->after('pickup_code');
            $table->boolean('is_paid')->default(false)->after('payment_method');
            $table->timestamp('paid_at')->nullable()->after('is_paid');
            $table->enum('status', ['pending', 'paid', 'completed', 'canceled'])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['pickup_code', 'payment_method', 'is_paid', 'paid_at']);
            $table->string('status')->default('pending')->change();
        });
    }
};
