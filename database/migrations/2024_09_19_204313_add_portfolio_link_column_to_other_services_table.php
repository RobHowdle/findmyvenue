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
        Schema::table('other_services', function (Blueprint $table) {
            $table->string('portfolio_link')->after('contact_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('other_services', function (Blueprint $table) {
            $table->dropColumn('portfolio_link');
        });
    }
};
