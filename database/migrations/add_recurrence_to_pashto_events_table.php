<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pashto_events', function (Blueprint $table) {
            $table->string('recurrence')->nullable()->default('none');
            $table->date('recurrence_end_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pashto_events', function (Blueprint $table) {
            $table->dropColumn(['recurrence', 'recurrence_end_date']);
        });
    }
};