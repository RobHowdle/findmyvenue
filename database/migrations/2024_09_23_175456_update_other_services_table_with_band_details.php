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
            $table->json('members')->after('working_times')->nullable();
            $table->json('stream_urls')->after('members')->nullable();
            $table->json('band_type')->after('stream_urls')->nullable();
            $table->json('genre')->after('band_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('other_services', function (Blueprint $table) {
            $table->dropColumn('members');
            $table->dropColumn('stream_urls');
            $table->dropColumn('band_type');
            $table->dropColumn('genre');
        });
    }
};
