<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('tenant')->create('grievances', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['open','closed','pending'])->default('open');
            $table->timestamp('opened_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('grievances');
    }
};
