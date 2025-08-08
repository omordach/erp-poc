<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('tenant')->create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
            $table->index('starts_at');
        });
    }
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('events');
    }
};
