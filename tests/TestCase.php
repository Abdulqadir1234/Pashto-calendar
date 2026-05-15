<?php

namespace Qadir\PashtoCalendar\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Qadir\PashtoCalendar\PashtoCalendarServiceProvider;

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
}