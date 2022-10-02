<?php

namespace asciito\BlogPackage\Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use asciito\BlogPackage\Tests\TestCase;

class InstallBlockPackageTest extends TestCase
{
    /** @test */
    public function the_install_command_copies_the_configuration()
    {
        // make sure we're starting from a clean state
        if ($this->configFileExists()) {
            unlink(config_path('blogpackage.php'));
        }

        $this->assertFalse($this->configFileExists());

        Artisan::call('blogpackage:install');

        $this->assertTrue($this->configFileExists());
    }

    /** @test */
    public function when_config_file_is_present_user_can_choose_to_not_overwrite_it()
    {
        // Given we already have a config file
        File::put(config_path('blogpackage.php'), 'test contents');
        $this->assertTrue($this->configFileExists());

        // When we run the install command
        $command = $this->artisan('blogpackage:install');

        // We expect a warning that our configuration file exists
        $command->expectsConfirmation(
            'Config file already exist. Do you want to overwrite it?',
            'no'
        );

        // We should see a message that our file was not overwritten
        $command->expectsOutput('Existing configuration was not overwritten');

        $this->assertEquals('test contents', file_get_contents(config_path('blogpackage.php')));
    }

    /** @test */
    public function when_a_config_file_is_present_users_can_choose_to_do_overwrite_it()
    {
        // Given we have already have an existing config file
        File::put(config_path('blogpackage.php'), 'test contents');
        $this->assertTrue(File::exists(config_path('blogpackage.php')));

        // When we run the install command
        $command = $this->artisan('blogpackage:install');

        // We expect a warning that our configuration file exists
        $command->expectsConfirmation(
            'Config file already exist. Do you want to overwrite it?',
            // When answered with "yes"
            'yes'
        );
        
        // execute the command to force override 
        $command->execute();

        $command->expectsOutput('Overwriting configuration file...');

        // Assert that the original contents are overwritten
        $this->assertEquals(
            file_get_contents(__DIR__.'/../../config/config.php'),
            file_get_contents(config_path('blogpackage.php'))
        );

        // Clean up
        unlink(config_path('blogpackage.php'));
    }


    private function configFileExists()
    {
        return File::exists(config_path('blogpackage.php'));
    }
}