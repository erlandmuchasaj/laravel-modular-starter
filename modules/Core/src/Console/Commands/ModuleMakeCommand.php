<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ModuleMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'module:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create blueprint for a new module';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Module';

    /**
     * Execute the console command.
     *
     * @return bool|null
     * @throws FileNotFoundException
     */
    public function handle(): ?bool
    {
        $this->generateModuleStructure();

        return true;
    }

    /**
     * Generate the entire structure of a module
     * @return void
     * @throws FileNotFoundException
     */
    public function generateModuleStructure(): void
    {
        $moduleName = $this->getModuleInput();

        // First we need to ensure that the given name is not a reserved word within the PHP
        // language and that the class name will actually be valid. If it is not valid we
        // can error now and prevent from polluting the filesystem using invalid files.
        if ($this->isReservedName($moduleName)) {
            $this->error('The name "' . $moduleName . '" is reserved by PHP.');
            return;
        }

        // Next, We will check to see if the Module folder already exists. If it does, we don't want
        // to create the Module and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this Module' files.
        if ($this->files->exists("modules/{$moduleName}")) {
            $this->error($this->type . " {$moduleName}, already exists!");
            return;
        }

        // Next we check that the name does not contain any non-supported values.
        if (preg_match('([^A-Za-z0-9_/\\\\])', $moduleName)) {
            throw new InvalidArgumentException('Module name contains invalid characters.');
        }

        /**
         * Create Module Folder Structures
         */
        $this->makeDirectory("modules/{$moduleName}");
        $this->makeDirectory("modules/{$moduleName}/bootstrap");
        $this->makeDirectory("modules/{$moduleName}/config");
        $this->makeDirectory("modules/{$moduleName}/database");
        $this->makeDirectory("modules/{$moduleName}/database/factories");
        $this->makeDirectory("modules/{$moduleName}/database/migrations");
        $this->makeDirectory("modules/{$moduleName}/database/seeders");

        if (version_compare(app()->version(), '9.0.0') >= 0) {
            // echo 'I am at least 9.0.0, my version: ' . app()->version() . "\n";
            $this->makeDirectory("modules/{$moduleName}/lang/en");
            $this->files->put("modules/{$moduleName}/lang/en.json", "");
            $this->files->put("modules/{$moduleName}/lang/en/messages.php", "<?php \n\n/*\n * You can place your custom module messages in here.\n */\n \nreturn [\n\n];\n");
        } else {
            $this->makeDirectory("modules/{$moduleName}/resources/lang/en");
            $this->files->put("modules/{$moduleName}/resources/lang/en/messages.php", "<?php \n\n/*\n * You can place your custom module messages in here.\n */\n \nreturn [\n\n];\n");
        }

        $this->makeDirectory("modules/{$moduleName}/resources/views/components");
        $this->makeDirectory("modules/{$moduleName}/resources/views/errors");
        $this->makeDirectory("modules/{$moduleName}/resources/views/layouts");
        $this->makeDirectory("modules/{$moduleName}/resources/views/layouts/includes");
        $this->makeDirectory("modules/{$moduleName}/resources/views/pages");
        $this->makeDirectory("modules/{$moduleName}/resources/views/partials");
        $this->makeDirectory("modules/{$moduleName}/routes");
        $this->makeDirectory("modules/{$moduleName}/tests");

        $this->makeDirectory("modules/{$moduleName}/src");
        $this->makeDirectory("modules/{$moduleName}/src/Http");
        $this->makeDirectory("modules/{$moduleName}/src/Console");
        $this->makeDirectory("modules/{$moduleName}/src/Models");
        $this->makeDirectory("modules/{$moduleName}/src/Providers");
        $this->makeDirectory("modules/{$moduleName}/src/Exceptions");
        $this->makeDirectory("modules/{$moduleName}/src/Console/Commands");
        $this->makeDirectory("modules/{$moduleName}/src/Http/Controllers");
        $this->makeDirectory("modules/{$moduleName}/src/Http/Middleware");
        $this->makeDirectory("modules/{$moduleName}/src/Http/Requests");

        if ($this->option('all')) {
            $this->makeDirectory("modules/{$moduleName}/src/Enums");
            $this->makeDirectory("modules/{$moduleName}/src/Broadcasting");
            $this->makeDirectory("modules/{$moduleName}/src/Events");
            $this->makeDirectory("modules/{$moduleName}/src/Http/Controllers/Api");
            $this->makeDirectory("modules/{$moduleName}/src/Http/Middleware/Api");
            $this->makeDirectory("modules/{$moduleName}/src/Http/Resources");
            $this->makeDirectory("modules/{$moduleName}/src/Http/ViewComposers");
            $this->makeDirectory("modules/{$moduleName}/src/Jobs");
            $this->makeDirectory("modules/{$moduleName}/src/Listeners");
            $this->makeDirectory("modules/{$moduleName}/src/Mail");
            $this->makeDirectory("modules/{$moduleName}/src/Notifications");
            $this->makeDirectory("modules/{$moduleName}/src/Observers");
            $this->makeDirectory("modules/{$moduleName}/src/Policies");
            $this->makeDirectory("modules/{$moduleName}/src/Repositories");
            $this->makeDirectory("modules/{$moduleName}/src/Rules");
            $this->makeDirectory("modules/{$moduleName}/src/Services");
            $this->makeDirectory("modules/{$moduleName}/src/Traits");
            $this->makeDirectory("modules/{$moduleName}/src/Utils");
            $this->makeDirectory("modules/{$moduleName}/src/Validators");
        }

        /**
         * Add .gitkeep files in folders in order to keep them in repositories
         * @note if we do not add .gitkeep the folder won't be pushed on repository.
         */

        $this->files->put("modules/{$moduleName}/bootstrap/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/config/config.php", "<?php \n\n/*\n * You can place your custom module configuration in here.\n */\n \nreturn [\n\n];\n");

        $this->files->put("modules/{$moduleName}/database/factories/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/database/migrations/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/database/seeders/.gitkeep", "");

        $this->files->put("modules/{$moduleName}/resources/views/components/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/resources/views/errors/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/resources/views/layouts/includes/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/resources/views/pages/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/resources/views/partials/.gitkeep", "");

        $stubWebRoute = $this->files->get($this->getStub() . "/routes/web.stub");
        $stubApiRoute = $this->files->get($this->getStub() . "/routes/api.stub");
        $stubBroadcastChannel = $this->files->get($this->getStub() . "/routes/channels.stub");

        $this->files->put("modules/{$moduleName}/routes/web.php", $this->writeFile($stubWebRoute, $moduleName));
        $this->files->put("modules/{$moduleName}/routes/api.php", $this->writeFile($stubApiRoute, $moduleName));
        $this->files->put("modules/{$moduleName}/routes/channels.php", $this->writeFile($stubBroadcastChannel, $moduleName));
        $this->files->put("modules/{$moduleName}/src/helpers.php", "<?php \n\n/*\n * You can place your custom helper functions.\n */");

        $this->buildProviderClass("Modules\\{$moduleName}\\Providers\\AppServiceProvider", $this->getStub() . "/Providers/AppServiceProvider.stub");
        $this->buildProviderClass("Modules\\{$moduleName}\\Providers\\EventServiceProvider", $this->getStub() . "/Providers/EventServiceProvider.stub");
        $this->buildProviderClass("Modules\\{$moduleName}\\Providers\\RouteServiceProvider", $this->getStub() . "/Providers/RouteServiceProvider.stub");
        $this->buildProviderClass("Modules\\{$moduleName}\\Providers\\SeedServiceProvider", $this->getStub() . "/Providers/SeedServiceProvider.stub");

        $this->files->put("modules/{$moduleName}/CHANGELOG.md", $this->files->get($this->getStub() . "/CHANGELOG.stub"));
        $this->files->put("modules/{$moduleName}/README.md", $this->files->get($this->getStub() . "/README.stub"));
        $this->files->put("modules/{$moduleName}/LICENSE", $this->files->get($this->getStub() . "/LICENSE.stub"));


        $this->writeComposerFile($moduleName);

        $this->requireModule($moduleName);

        $this->info($this->type . ' created successfully.');

        exec("composer update");
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
     * Build the directory for the class if necessary.
     *
     * @param string $path
     * @return string
     */
    protected function makeDirectory($path): string
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/stubs/module';
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @param string $stubPath
     * @return int|bool
     *
     * @throws FileNotFoundException
     */
    protected function buildProviderClass(string $name, string $stubPath): int|bool
    {
        $stub = $this->files->get($stubPath);

        $moduleName = $this->getModuleInput();

        $class = str_replace($this->getNamespace($name) . '\\', '', $name);

        $filePath = "modules" . DIRECTORY_SEPARATOR . $moduleName . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . "Providers" . DIRECTORY_SEPARATOR . $class . ".php";

        return $this->files->put(
            $filePath,
            $this->replaceNamespace($stub, $name)->replaceModuleName($stub, $moduleName)->replaceClass($stub, $name)
        );
    }

    /**
     * @param string $moduleName
     * @return void
     *
     * @throws FileNotFoundException
     */
    public function writeComposerFile(string $moduleName): void
    {
        $content = $this->files->get($this->getStub() . "/composer.stub");

        $snakeModuleName = Str::kebab($moduleName);

        $content = str_replace(['SnakeModuleName', '{{ class }}', '{{class}}'], $snakeModuleName, $content);

        $content = str_replace(['ModuleName', '{{ class }}', '{{class}}'], $moduleName, $content);

        $this->files->put("modules/{$moduleName}/composer.json", $content);
    }


    /**
     * @param string $stub
     * @param string $moduleName
     * @return string
     */
    public function writeFile(string $stub, string $moduleName): string
    {
        $snakeModuleName = Str::kebab($moduleName);

        $stub = str_replace(
            ['DummyModuleName', '{{ moduleName }}', '{{moduleName}}'],
            $moduleName,
            $stub
        );

        return str_replace(
            ['DummySnakeModuleName', '{{ snakeModuleName }}', '{{snakeModuleName}}'],
            $snakeModuleName,
            $stub
        );
    }

    /**
     * Put the new module on the main json file of Laravel
     * @param string $moduleName
     * @return void
     * @throws FileNotFoundException
     */
    public function requireModule(string $moduleName): void
    {
        $snakeModuleName = Str::kebab($moduleName);
        $content = $this->files->get("composer.json");
        $phpArray = json_decode($content, true);
        $phpArray['require']['modules/' . $snakeModuleName] = '^1.0';
        $this->files->put("composer.json", json_encode($phpArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Replace the Module class name for the given stub.
     *
     * @param string $stub
     * @param string $moduleName
     * @return $this
     */
    protected function replaceModuleName(string &$stub, string $moduleName): static
    {
        $stub = str_replace(
            ['DummyModuleName', '{{ moduleName }}', '{{moduleName}}'],
            $moduleName,
            $stub
        );

        return $this;
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
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['all', 'a', InputOption::VALUE_NONE, 'Generate a module with all folder structure.'],
        ];
    }
}
