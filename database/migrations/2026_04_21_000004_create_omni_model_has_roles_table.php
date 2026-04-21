<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('omni_model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->index(['model_id', 'model_type'], 'omni_model_has_roles_model_id_model_type_index');

            $table->foreign('role_id')
                ->references('id')
                ->on('omni_roles')
                ->onDelete('cascade');

            $table->primary(['role_id', 'model_id', 'model_type'],
                'omni_model_has_roles_role_model_type_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('omni_model_has_roles');
    }
};
