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
        Schema::table('events', function (Blueprint $table) {
            $table->string('ticket_url')->nullable()->after('band_ids');
            $table->string('on_the_door_ticket_price')->after('ticket_url');
            $table->time('event_start_time')->nullable()->after('event_date');
            $table->time('event_end_time')->nullable()->after('event_start_time');
            $table->string('facebook_event_url')->nullable()->after('event_end_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['ticket_url', 'event_start_time', 'event_end_time', 'facebook_event_url', 'on_the_door_ticket_price']);
        });
    }
};
