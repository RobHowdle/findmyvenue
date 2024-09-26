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
        Schema::create('finances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('serviceable_id');
            $table->string('serviceable_type');
            $table->string('finance_type');
            $table->string('name');
            $table->date('date_from');
            $table->date('date_to');
            $table->string('external_link')->nullable();
            $table->json('incoming');
            $table->json('other_incoming')->nullable();
            $table->json('outgoing');
            $table->json('other_outgoing')->nullable();
            $table->decimal('desired_profit', 10, 2)->default('0');
            $table->decimal('total_incoming', 10, 2)->default('0');
            $table->decimal('total_outgoing', 10, 2)->default('0');
            $table->decimal('total_profit', 10, 2)->default('0');
            $table->decimal('total_remaining_to_desired_profit', 10, 2)->default('0');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finances');
    }
};