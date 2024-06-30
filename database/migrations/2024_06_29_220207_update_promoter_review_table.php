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
        Schema::table('promoter_reviews', function (Blueprint $table) {
            $table->string('reviewer_ip')->after('author');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promoter_reviews', function (Blueprint $table) {
            $table->dropColumn('reviewer_ip');
        });
    }
};
