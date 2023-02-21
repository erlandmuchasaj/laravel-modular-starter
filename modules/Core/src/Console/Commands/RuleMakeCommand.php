<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'module:make-rule')]
class RuleMakeCommand extends BaseGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-rule';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'module:make-rule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new validation rule';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Rule';

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws FileNotFoundException
     */
    protected function buildClass($name): string
    {
        return str_replace(
            '{{ ruleType }}',
            $this->option('implicit') ? 'ImplicitRule' : 'Rule',
            parent::buildClass($name)
        );
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        //  $stub = '/stubs/rule.stub';
        //
        //  if ($this->option('invokable')) {
        //      $stub = '/stubs/rule.invokable.stub';
        //  }
        //
        //  if ($this->option('implicit') && $this->option('invokable')) {
        //      $stub = str_replace('.stub', '.implicit.stub', $stub);
        //  }

        $stub = $this->option('implicit')
            ? '/stubs/rule.implicit.stub'
            : '/stubs/rule.stub';

        return $this->resolveStubPath($stub);
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\\Rules';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the rule already exists'],
            ['implicit', 'i', InputOption::VALUE_NONE, 'Generate an implicit rule.'],
            ['invokable', null, InputOption::VALUE_NONE, 'Generate a single method, invokable rule class.'],
        ];
    }
}
