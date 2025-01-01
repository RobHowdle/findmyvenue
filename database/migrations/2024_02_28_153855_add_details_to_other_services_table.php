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
            $table->string('contact_number')->after('location');
            $table->string('contact_email')->after('contact_number');
            $table->json('contact_link')->after('contact_email');
            $table->string('services')->after('contact_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('other_services', function (Blueprint $table) {
            $table->dropColumn('contact_number');
            $table->dropColumn('contact_email');
            $table->dropColumn('contact_link');
            $table->dropColumn('services');
        });
    }
};
