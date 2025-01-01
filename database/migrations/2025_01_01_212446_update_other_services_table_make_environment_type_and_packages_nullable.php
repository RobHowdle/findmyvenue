<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('other_services', function (Blueprint $table) {
            // Make the columns nullable
            $table->json('environment_type')->nullable()->change();
            $table->json('packages')->nullable()->change();
            $table->json('working_times')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('other_services', function (Blueprint $table) {
            // Revert columns back to non-nullable
            $table->json('environment_type')->nullable(false)->change();
            $table->json('packages')->nullable(false)->change();
            $table->json('working_times')->nullable(false)->change();
        });
    }
};
