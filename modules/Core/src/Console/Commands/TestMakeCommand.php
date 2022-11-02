<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class TestMakeCommand extends BaseGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-test';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'module:make-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new test class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Test';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        $suffix = $this->option('unit') ? '.unit.stub' : '.stub';

        return $this->option('pest')
            ? $this->resolveStubPath('/stubs/pest'.$suffix)
            : $this->resolveStubPath('/stubs/test'.$suffix);
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name): string
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        $moduleName = $this->getModuleInput();

        return base_path()."/modules/{$moduleName}/tests/".str_replace('\\', '/', $name).'.php';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        if ($this->option('unit')) {
            return $rootNamespace.'\\Unit';
        } else {
            return $rootNamespace.'\\Feature';
        }
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace(): string
    {
        $moduleName = $this->getModuleInput();

        return "Modules\\{$moduleName}\\Tests";
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['unit', 'u', InputOption::VALUE_NONE, 'Create a unit test.'],
            ['pest', 'p', InputOption::VALUE_NONE, 'Create a Pest test.'],
        ];
    }
}
