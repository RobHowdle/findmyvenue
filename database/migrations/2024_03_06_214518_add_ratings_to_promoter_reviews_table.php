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
            $table->string('communication_rating')->after('promoter_id');
            $table->string('rop_rating')->after('communication_rating');
            $table->string('promotion_rating')->after('rop_rating');
            $table->string('quality_rating')->after('promotion_rating');
            $table->string('review_approved')->after('author')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promoter_reviews', function (Blueprint $table) {
            $table->dropColumn('communication_rating');
            $table->dropColumn('rop_rating');
            $table->dropColumn('promotion_rating');
            $table->dropColumn('quality_rating');
            $table->dropColumn('review_approved');
        });
    }
};