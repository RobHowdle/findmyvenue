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
            $table->string('logo_url')->nullable()->after('name');
            $table->string('postal_town')->after('location');
            $table->string('longitude')->after('postal_town');
            $table->string('latitude')->after('longitude');
            $table->unsignedBigInteger('other_service_id')->after('latitude');
            $table->json('packages')->after('other_service_id');
            $table->json('environment_type')->after('packages');
            $table->json('working_times')->after('environment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('other_services', function (Blueprint $table) {
            $table->dropColumn('logo_url');
            $table->dropColumn('other_service_id');
            $table->dropColumn('postal_town');
            $table->dropColumn('longitude');
            $table->dropColumn('latitude');
            $table->dropColumn('packages');
            $table->dropColumn('environment_type');
            $table->dropColumn('working_times');
        });
    }
};
