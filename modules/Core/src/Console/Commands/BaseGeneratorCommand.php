<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

abstract class BaseGeneratorCommand extends GeneratorCommand
{
    /**
     * $this->laravel->basePath(): c:\wamp\www\starter
     * app()->basePath()
     * base_path()
     */

    /**
     * Get the destination class path.
     *
     * @param string $name
     * @return string
     */
    protected function getPath($name): string
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        $moduleName = $this->getModuleInput();

        return base_path() . "/modules/{$moduleName}/src/" . str_replace('\\', '/', $name) . '.php';
    }

    /**
     * Get the first view directory path from the application configuration.
     *
     * @param  string  $path
     * @return string
     */
    protected function viewPath($path = ''): string
    {
        $moduleName = $this->getModuleInput();

        $views =  base_path() . "/modules/{$moduleName}/resources/views";

        return $views.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath(string $stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getModuleInput(): string
    {
        return Str::of(strval($this->argument('module')))->trim()->studly();
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace(): string
    {
        $moduleName = $this->getModuleInput();
        return "Modules\\{$moduleName}\\";
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['module', InputArgument::REQUIRED, 'Module name'],
            ['name', InputArgument::REQUIRED, 'The name of the class'],
        ];
    }
}
