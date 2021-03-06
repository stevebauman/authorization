<?php

namespace Stevebauman\Authorization\Tests;

use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Stevebauman\Authorization\AuthorizationServiceProvider;
use Stevebauman\Authorization\Tests\Stubs\Permission;
use Stevebauman\Authorization\Tests\Stubs\Role;

class TestCase extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        // Create the users table for testing
        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name');
        });

        $this->artisan('migrate', [
            '--realpath' => realpath(__DIR__.'/../src/Migrations'),
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            AuthorizationServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('authorization.role', Role::class);
        $app['config']->set('authorization.permission', Permission::class);
    }
}
