<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('omni_audit_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('permission')->nullable();
            $table->string('reason')->nullable(); // hierarchy_denial, missing_permission, etc.
            $table->string('result')->default('denied'); // granted or denied
            $table->string('causer_type')->nullable();
            $table->unsignedBigInteger('causer_id')->nullable();
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->json('properties')->nullable();
            $table->integer('rank_delta')->nullable(); // difference in rank (if hierarchy denial)
            $table->boolean('is_ghost')->default(false);
            $table->timestamps();

            $table->index(['causer_id', 'causer_type'], 'omni_audit_log_causer_index');
            $table->index(['subject_id', 'subject_type'], 'omni_audit_log_subject_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('omni_audit_log');
    }
};
