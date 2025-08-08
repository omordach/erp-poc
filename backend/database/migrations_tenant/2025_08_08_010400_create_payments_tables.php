<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('tenant')->create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->decimal('amount', 12, 2)->default(0);
            $table->enum('status', ['draft','issued','paid','void'])->default('draft');
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamps();
        });

        Schema::connection('tenant')->create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->decimal('amount', 12, 2)->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->string('method')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('payments');
        Schema::connection('tenant')->dropIfExists('invoices');
    }
};
