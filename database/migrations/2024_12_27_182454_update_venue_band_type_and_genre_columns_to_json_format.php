<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('venues')->whereRaw('JSON_VALID(band_type) = 0')->update([
            'band_type' => DB::raw('NULL'),
            'genre' => DB::raw('NULL'),
        ]);

        Schema::table('venues', function (Blueprint $table) {
            $table->json('band_type')->nullable(false)->change();
        });

        Schema::table('venues', function (Blueprint $table) {
            $table->json('genre')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            $table->text('band_type')->change();
            $table->text('genre')->change();
        });
    }
};