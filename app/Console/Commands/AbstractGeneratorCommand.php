<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

abstract class AbstractGeneratorCommand extends GeneratorCommand
{
    public function handle()
    {
        // First we need to ensure that the given name is not a reserved word within the PHP
        // language and that the class name will actually be valid. If it is not valid we
        // can error now and prevent from polluting the filesystem using invalid files.
        if ($this->isReservedName($this->getNameInput())) {
            $this->error('The name "'.$this->getNameInput().'" is reserved by PHP.');

            return false;
        }

        $name = $this->qualifyClass($this->getNameInput());

        $path = $this->getPath($name);

        // Next, We will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ((! $this->hasOption('force') ||
                ! $this->option('force')) &&
            $this->alreadyExists($this->getNameInput())) {
            $this->error($this->type.' already exists!');

            return false;
        }

        $this->call('make:feature', [
            '--feature' => $this->getFeature()
        ]);
        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $this->files->put($path, $this->sortImports($this->buildClass($name)));

        $this->info($this->type.' created successfully.');
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['feature', null, InputOption::VALUE_REQUIRED, 'The name of the feature']
        ];
    }

    protected function getFeature()
    {
        return Str::title($this->option('feature'));
    }

    protected function rootNamespace()
    {
        $feature = $this->getFeature();

        if (empty($feature)) {
            throw new LogicException("You must declare your --feature= flag");
        }

        return sprintf('%s%s\\', $this->laravel->getNamespace(), $feature);
    }

    protected function stubPath($path = '', $feature = '')
    {
        $views = trim('resources/stubs/' . $feature, '/');

        return $views.($path ? DIRECTORY_SEPARATOR.trim($path, '/') : $path);
    }
}
