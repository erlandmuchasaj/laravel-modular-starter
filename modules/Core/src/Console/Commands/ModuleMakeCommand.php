<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Symfony\Component\Console\Input\InputArgument;

class ModuleMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make';

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
     * The console command name.
     *
     * @var Filesystem
     */
    // protected Filesystem $files;

    /**
     * Create a new controller creator command instance.
     *
     * @param Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        // $this->files = $files;
    }


    /**
     * Execute the console command.
     *
     * @return int
     * @throws FileNotFoundException
     */
    public function handle(): int
    {
        $this->generateModuleStructure();

        return CommandAlias::SUCCESS;
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
            $this->error('The name "'.$moduleName.'" is reserved by PHP.');
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
        $this->makeDirectory("modules/{$moduleName}/resources");
        $this->makeDirectory("modules/{$moduleName}/resources/lang");
        $this->makeDirectory("modules/{$moduleName}/resources/lang/en");
        $this->makeDirectory("modules/{$moduleName}/resources/views");
        $this->makeDirectory("modules/{$moduleName}/resources/views/components");
        $this->makeDirectory("modules/{$moduleName}/resources/views/errors");
        $this->makeDirectory("modules/{$moduleName}/resources/views/layouts");
        $this->makeDirectory("modules/{$moduleName}/resources/views/layouts/includes");
        $this->makeDirectory("modules/{$moduleName}/resources/views/pages");
        $this->makeDirectory("modules/{$moduleName}/resources/views/partials");
        $this->makeDirectory("modules/{$moduleName}/routes");
        $this->makeDirectory("modules/{$moduleName}/tests");

        $this->makeDirectory("modules/{$moduleName}/src");
        $this->makeDirectory("modules/{$moduleName}/src/Console");
        $this->makeDirectory("modules/{$moduleName}/src/Console/Commands");
        $this->makeDirectory("modules/{$moduleName}/src/Enums");
        $this->makeDirectory("modules/{$moduleName}/src/Events");
        $this->makeDirectory("modules/{$moduleName}/src/Exceptions");
        $this->makeDirectory("modules/{$moduleName}/src/Http");
        $this->makeDirectory("modules/{$moduleName}/src/Http/Controllers");
        $this->makeDirectory("modules/{$moduleName}/src/Http/Controllers/Api");
        $this->makeDirectory("modules/{$moduleName}/src/Http/Middleware");
        $this->makeDirectory("modules/{$moduleName}/src/Http/Middleware/Api");
        $this->makeDirectory("modules/{$moduleName}/src/Http/Requests");
        $this->makeDirectory("modules/{$moduleName}/src/Http/Resources");
        $this->makeDirectory("modules/{$moduleName}/src/Http/ViewComposers");
        $this->makeDirectory("modules/{$moduleName}/src/Jobs");
        $this->makeDirectory("modules/{$moduleName}/src/Listeners");
        $this->makeDirectory("modules/{$moduleName}/src/Models");
        $this->makeDirectory("modules/{$moduleName}/src/Notifications");
        $this->makeDirectory("modules/{$moduleName}/src/Observers");
        $this->makeDirectory("modules/{$moduleName}/src/Policies");
        $this->makeDirectory("modules/{$moduleName}/src/Providers");
        $this->makeDirectory("modules/{$moduleName}/src/Repositories");
        $this->makeDirectory("modules/{$moduleName}/src/Rules");
        $this->makeDirectory("modules/{$moduleName}/src/Services");
        $this->makeDirectory("modules/{$moduleName}/src/Traits");
        $this->makeDirectory("modules/{$moduleName}/src/Validators");

        /**
         * Add .gitkeep files in folders in order to keep them in repositories
         * @note if we do not add .gitkeep the folder won't be pushed on repository.
         */

        $this->files->put("modules/{$moduleName}/bootstrap/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/config/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/config/config.php", "<?php \n\n/*\n * You can place your custom module configuration in here.\n */\n \nreturn [\n\n];\n");

        $this->files->put("modules/{$moduleName}/database/factories/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/database/migrations/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/database/seeders/.gitkeep", "");

        $this->files->put("modules/{$moduleName}/resources/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/resources/lang/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/resources/lang/en/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/resources/lang/en/messages.php", "<?php \n\n/*\n * You can place your custom module messages in here.\n */\n \nreturn [\n\n];\n");
        $this->files->put("modules/{$moduleName}/resources/views/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/resources/views/components/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/resources/views/errors/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/resources/views/layouts/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/resources/views/layouts/includes/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/resources/views/pages/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/resources/views/partials/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/routes/.gitkeep", "");
        $this->files->put("modules/{$moduleName}/routes/api.php", "<?php \n\n/*\n * You can place your custom module routes for api.\n */");
        $this->files->put("modules/{$moduleName}/routes/web.php", "<?php \n\n/*\n * You can place your custom module routes for web.\n */");
        $this->files->put("modules/{$moduleName}/src/helpers.php", "<?php \n\n ");

        $this->buildProviderClass("Modules\\{$moduleName}\\Providers\\AppServiceProvider", __DIR__ . "/stubs/module/Providers/AppServiceProvider.stub");
        $this->buildProviderClass("Modules\\{$moduleName}\\Providers\\EventServiceProvider", __DIR__ . "/stubs/module/Providers/EventServiceProvider.stub");
        $this->buildProviderClass("Modules\\{$moduleName}\\Providers\\RouteServiceProvider", __DIR__ . "/stubs/module/Providers/RouteServiceProvider.stub");
        $this->buildProviderClass("Modules\\{$moduleName}\\Providers\\SeedServiceProvider", __DIR__ . "/stubs/module/Providers/SeedServiceProvider.stub");

        $this->files->put("modules/{$moduleName}/CHANGELOG.md", $this->files->get(__DIR__ . "/stubs/module/CHANGELOG.stub"));
        $this->files->put("modules/{$moduleName}/README.md", $this->files->get(__DIR__ . "/stubs/module/README.stub"));
        $this->files->put("modules/{$moduleName}/LICENSE", $this->files->get(__DIR__ . "/stubs/module/LICENSE.stub"));

        $this->writeComposerFile($moduleName);

        $this->requireModule($moduleName);

        $this->info($this->type.' created successfully.');

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
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path): string
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0755, true, true);
        }

        return $path;
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
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__.'/stubs/module';
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

        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        $filePath = "modules". DIRECTORY_SEPARATOR . $moduleName . DIRECTORY_SEPARATOR . 'src' .DIRECTORY_SEPARATOR . "Providers" . DIRECTORY_SEPARATOR . $class.".php";

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
        $content = $this->files->get(__DIR__ . "/stubs/module/composer.stub");

        $snakeModuleName = Str::kebab($moduleName);

        $content = str_replace(['SnakeModuleName', '{{ class }}', '{{class}}'], $snakeModuleName, $content);

        $content = str_replace(['ModuleName', '{{ class }}', '{{class}}'], $moduleName, $content);

        $this->files->put("modules/{$moduleName}/composer.json", $content);
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
        $phpArray['require']['modules/'.$snakeModuleName] = '~1.0';
        $this->files->put("composer.json", json_encode($phpArray, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
    }


    /**
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name): static
    {
        $searches = [
            ['DummyNamespace', 'DummyRootNamespace', 'NamespacedDummyUserModel'],
            ['{{ namespace }}', '{{ rootNamespace }}', '{{ namespacedUserModel }}'],
            ['{{namespace}}', '{{rootNamespace}}', '{{namespacedUserModel}}'],
        ];

        foreach ($searches as $search) {
            $stub = str_replace(
                $search,
                [$this->getNamespace($name), $this->rootNamespace(), $this->userProviderModel()],
                $stub
            );
        }

        return $this;
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
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name): string
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        return str_replace(['DummyClass', '{{ class }}', '{{class}}'], $class, $stub);
    }

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getNamespace($name): string
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace(): string
    {
        $moduleName = $this->getModuleInput();
        return "Modules\\{$moduleName}";
    }

}
