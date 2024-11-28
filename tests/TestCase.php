<?php

namespace EvoMark\LaravelIdObfuscator\Tests;

use EvoMark\LaravelIdObfuscator\Provider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            Provider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {

        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $migration = require __DIR__ . '/Database/migrations/create_users_table.php';

        $migration->up();
    }
}
