<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pashto_holidays', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('month');
            $table->integer('day');
            $table->string('name');               // Pashto/Dari name
            $table->string('name_en')->nullable(); // English fallback
            
            $table->date('gregorian_date')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->string('type')->nullable();    // e.g., "Public"
            $table->json('raw_data')->nullable();
            $table->timestamps();

            $table->unique(['year', 'month', 'day']);
            $table->index(['year', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pashto_holidays');
    }
};