<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('tenant')->create('unions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->timestamps();
        });

        Schema::connection('tenant')->create('locals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('union_id')->constrained('unions')->cascadeOnDelete();
            $table->string('name');
            $table->string('number');
            $table->timestamps();
        });

        Schema::connection('tenant')->create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('local_id')->constrained('locals')->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('members');
        Schema::connection('tenant')->dropIfExists('locals');
        Schema::connection('tenant')->dropIfExists('unions');
    }
};
