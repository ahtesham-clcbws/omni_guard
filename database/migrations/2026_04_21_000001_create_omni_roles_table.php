<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('omni_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->integer('sort_order')->default(999); // lower = higher rank
            $table->json('inherits_from')->nullable();  // parent role IDs
            $table->boolean('is_ghost')->default(false);
            $table->string('scope')->default('global'); // global or tenant
            $table->timestamps();

            $table->unique(['name', 'guard_name', 'scope']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('omni_roles');
    }
};
