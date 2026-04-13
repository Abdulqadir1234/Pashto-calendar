<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pashto_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();

            $table->integer('year');
            $table->integer('month');
            $table->integer('day');

            $table->string('time')->nullable();
            $table->string('color')->default('blue');

            $table->timestamps();

            $table->index(['year', 'month', 'day']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pashto_events');
    }
};