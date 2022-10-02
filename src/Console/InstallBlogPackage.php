<?php

namespace asciito\BlogPackage\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallBlogPackage extends Command
{
    protected $signature = 'blogpackage:install';

    protected $description = 'Install the BlogPackage';


    public function handle()
    {
        $this->info('Installing  BlogPackage...');

        $this->info('Publishing configuration...');

        if (!$this->configExists('blogpackage.php')) {
            $this->publishConfiguration();
            $this->info('Published configuration');
        } else {
            if ($this->shouldOverrideConfig()) {
                $this->info('Overwriting configuration file...');
                $this->publishConfiguration($force = true);
            } else {
                $this->info('Existing configuration was not overwritten');
            }
        }

        $this->info('Installed BlogPackage');
    }

    private function configExists($fileName)
    {
        return File::exists(config_path($fileName));
    }

    private function shouldOverrideConfig()
    {
        return $this->confirm(
            'Config file already exist. Do you want to overwrite it?',
            false
        );
    }

    private function publishConfiguration($forcePublish = false)
    {
        $params = [
            '--provider' => "asciito\BlogPackage\BlogPackageServiceProvider",
            '--tag' => "config"
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
    }
}