<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('tenant')->create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('module_key'); // e.g., 'membership','grievances','payments','events'
            $table->string('role');       // 'viewer'|'editor'|'admin'
            $table->unsignedBigInteger('union_id')->nullable();
            $table->unsignedBigInteger('local_id')->nullable();
            $table->timestamps();
            $table->index(['user_id','module_key']);
        });
    }
    public function down(): void
    {
        Schema::connection('tenant')->dropIfExists('user_roles');
    }
};
