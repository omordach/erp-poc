<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('landlord')->create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();      // e.g., 'opeiu33'
            $table->string('name');               // display name
            $table->string('database')->unique(); // tenant DB name
            $table->json('data')->nullable();     // misc config
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('landlord')->dropIfExists('tenants');
    }
};
