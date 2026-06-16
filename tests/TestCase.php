<?php

namespace Qadir\PashtoCalendar\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Qadir\PashtoCalendar\PashtoCalendarServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class TestCase extends Orchestra
{
    /**
     * Call this in a beforeEach() for tests that need a real database.
     */
    protected function requireDatabase(): void
    {
        if (!extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('SQLite extension is required for this test.');
        }
    }

    protected function getPackageProviders($app): array
    {
        return [
            PashtoCalendarServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('pashto-calendar.language', 'ps');
        $app['config']->set('pashto-calendar.demo_route', true);
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Only create tables if SQLite is available
        if (extension_loaded('pdo_sqlite')) {
            $this->createPashtoEventsTable();
            $this->createPashtoHolidaysTable();
        }
    }

    protected function createPashtoEventsTable(): void
    {
        if (Schema::hasTable('pashto_events')) {
            return;
        }

        Schema::create('pashto_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('year');
            $table->integer('month');
            $table->integer('day');
            $table->string('time')->nullable();
            $table->string('color')->default('#3b82f6');
            $table->string('recurrence')->nullable()->default('none');
            $table->date('recurrence_end_date')->nullable();
            $table->timestamps();
            $table->index(['year', 'month', 'day']);
        });
    }

    protected function createPashtoHolidaysTable(): void
    {
        if (Schema::hasTable('pashto_holidays')) {
            return;
        }

        Schema::create('pashto_holidays', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('month');
            $table->integer('day');
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->json('raw_data')->nullable();
            $table->timestamps();
            $table->unique(['year', 'month', 'day']);
        });
    }
}