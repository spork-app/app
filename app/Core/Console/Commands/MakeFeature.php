<?php

namespace App\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ProviderMakeCommand;
use Illuminate\Support\Str;

class MakeFeature extends ProviderMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:feature {--feature=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new service provider under the feature namespace.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filesystem = new Filesystem();

        $path = base_path('app/' . Str::title($this->option('feature')));
        if ($filesystem->exists($path)) {
            return 0;
        }

        $filesystem->makeDirectory($path);

        return 0;
    }

    protected function getStub()
    {
        return base_path('resources/stub/MakeFeature');
    }
}
