<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ResourceMakeCommand extends BaseGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-resource';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'module:make-resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new resource';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource';

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws FileNotFoundException
     */
    public function handle(): ?bool
    {
        if ($this->collection()) {
            $this->type = 'Resource collection';
        }

        return parent::handle();
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return $this->collection()
            ? $this->resolveStubPath('/stubs/resource-collection.stub')
            : $this->resolveStubPath('/stubs/resource.stub');
    }

    /**
     * Determine if the command is generating a resource collection.
     *
     * @return bool
     */
    protected function collection(): bool
    {
        return $this->option('collection') ||
            Str::endsWith($this->argument('name'), 'Collection');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\\Http\\Resources';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['collection', 'c', InputOption::VALUE_NONE, 'Create a resource collection'],
        ];
    }
}
