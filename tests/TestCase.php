<?php

namespace Stilldesign\Translations\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Waavi\Translation\TranslationServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'testing']);

        $this->withFactories(__DIR__.'/../database/factories');

    }

    protected function getPackageProviders($app)
    {
        return [
            TranslationServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('app.key', 'sF5r4kJy5HEcOEx3NWxUcYj1zLZLHxuu');
        $app['config']->set('translator.source', 'database');
    }
}
