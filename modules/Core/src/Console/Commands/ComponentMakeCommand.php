<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ComponentMakeCommand extends BaseGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-component';


    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     */
    protected static $defaultName = 'module:make-component';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new component-class for the specified module.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Component';

    /**
     * Execute the console command.
     *
     * @return bool|null
     * @throws FileNotFoundException
     */
    public function handle(): ?bool
    {
        if ($this->option('view')) {
            $this->writeView(function () {
                $this->info($this->type.' created successfully.');
            });
            return null;
        }

        if (parent::handle() === false && !$this->option('force')) {
            return false;
        }

        if (!$this->option('inline')) {
            $this->writeView();
        }

        return true;
    }

    /**
     * Write the view for the component.
     *
     * @param  callable|null  $onSuccess
     *
     * @return void
     */
    protected function writeView(callable $onSuccess = null)
    {
        $path = $this->viewPath(
            str_replace('.', '/', 'components.' . $this->getView()) . '.blade.php'
        );

        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        if ($this->files->exists($path) && !$this->option('force')) {
            $this->error('View already exists!');

            return;
        }

        file_put_contents(
            $path,
            '<div>
    <!-- ' . Inspiring::quote() . ' -->
</div>'
        );

        if ($onSuccess) {
            $onSuccess();
        }
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @return string
     * @throws FileNotFoundException
     */
    protected function buildClass($name): string
    {
        if ($this->option('inline')) {
            return str_replace(
                'DummyView',
                "<<<'blade'\n<div>\n    <!-- " . Inspiring::quote() . " -->\n</div>\nblade",
                parent::buildClass($name)
            );
        }

        return str_replace(
            'DummyView',
            'view(\'components.' . $this->getView() . '\')',
            parent::buildClass($name)
        );
    }

    /**
     * Get the view name relative to the components' directory.
     *
     * @return string view
     */
    protected function getView(): string
    {
        $name = str_replace('\\', '/', $this->argument('name'));

        return collect(explode('/', $name))
            ->map(function ($part) {
                return Str::kebab($part);
            })
            ->implode('.');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/view-component.stub');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\\View\\Components';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the component already exists'],
            ['inline', null, InputOption::VALUE_NONE, 'Create a component that renders an inline view'],
            ['view', null, InputOption::VALUE_NONE, 'Create an anonymous component with only a view'],
        ];
    }
}
