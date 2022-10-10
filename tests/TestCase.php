<?php

namespace asciito\BlogPackage\Tests;

use asciito\BlogPackage\BlogPackageServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            BlogPackageServiceProvider::class,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
        include_once __DIR__ . '/../database/migrations/create_posts_table.php.stub';
        include_once __DIR__ . '/../database/migrations/create_users_table.php.stub';
        
        (new \CreatePostsTable)->up();
        (new \CreateUsersTable)->up();
    }
}
