<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->dateTime('job_start_date');
            $table->dateTime('job_end_date');
            $table->string('scope')->nullable();
            $table->string('scope_url')->nullable();
            $table->string('job_type');
            $table->string('estimated_amount');
            $table->string('final_amount');
            $table->string('job_status');
            $table->string('priority');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
