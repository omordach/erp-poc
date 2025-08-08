<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('tenant')->create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->index();
            $table->enum('status', ['active','frozen','closed'])->default('active');
            $table->timestamp('opened_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('accounts');
    }
};
