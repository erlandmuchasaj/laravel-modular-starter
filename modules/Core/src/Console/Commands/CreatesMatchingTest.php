<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

trait CreatesMatchingTest
{
    /**
     * Add the standard command options for generating matching tests.
     *
     * @return void
     */
    protected function addTestOptions()
    {
        foreach (['test' => 'PHPUnit', 'pest' => 'Pest'] as $option => $name) {
            $this->getDefinition()->addOption(new InputOption(
                $option,
                null,
                InputOption::VALUE_NONE,
                "Generate an accompanying {$name} test for the {$this->type}"
            ));
        }
    }

    /**
     * Create the matching test case if requested.
     *
     * @param  string  $path
     * @return void
     */
    protected function handleTestCreation(string $path): void
    {
        if (! $this->option('test') && ! $this->option('pest')) {
            return;
        }

        $this->call('module:make-test', [
            'module' => $this->getModuleInput(),
            'name' => Str::of($path)->after($this->laravel['path'])->beforeLast('.php')->append('Test')->replace('\\', '/'),
            '--pest' => $this->option('pest'),
        ]);
    }
}
