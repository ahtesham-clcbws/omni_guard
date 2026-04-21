<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('omni_impersonations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('impersonator_id');
            $table->unsignedBigInteger('impersonated_id');
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('ended_at')->nullable();
            $table->string('reason')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index('impersonator_id');
            $table->index('impersonated_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('omni_impersonations');
    }
};
