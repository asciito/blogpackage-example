<?php

namespace asciito\BlogPackage\Tests;

use asciito\BlogPackage\BlogPackageServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
            BlogPackageServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
        include_once __DIR__ . '/../database/migrations/create_posts_table.php.stub';
        include_once __DIR__ . '/../database/migrations/create_users_table.php.stub';
        
        (new \CreatePostsTable)->up();
        (new \CreateUsersTable)->up();
    }
}
